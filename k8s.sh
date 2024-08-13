#!/bin/bash

# Define variables
KUBERNETES_VERSION="1.31.0"
KUBERNETES_VERSION_SHORT="1.31"
MASTER_IP="0.0.0.0"  # Bind the Kubernetes API server to all IP addresses
DOMAIN_NAME="k8s.cloud-deployments.dev"
EMAIL="wilderomnoah@gmail.com"

# Update system and install necessary packages
echo "Updating system and installing dependencies..."
sudo apt-get update && sudo apt-get install -y apt-transport-https ca-certificates curl || { echo "Failed to install dependencies"; exit 1; }

ufw allow 443
ufw allow 80
ufw allow 30080
ufw allow 30443

# Remove old container runtime packages
echo "Removing old container runtime packages..."
for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do
  sudo apt-get remove -y $pkg || true
done

# Install Docker
echo "Installing Docker..."
sudo apt-get install -y ca-certificates curl gnupg || { echo "Failed to install Docker dependencies"; exit 1; }
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo tee /etc/apt/keyrings/docker.asc > /dev/null
sudo chmod 0644 /etc/apt/keyrings/docker.asc
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update && sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin || { echo "Failed to install Docker"; exit 1; }
sudo systemctl enable docker && sudo systemctl start docker || { echo "Failed to start Docker"; exit 1; }

# Install Kubernetes CLI (kubectl)
echo "Installing Kubernetes CLI (kubectl)..."
curl -LO "https://dl.k8s.io/release/v${KUBERNETES_VERSION}/bin/linux/amd64/kubectl" || { echo "Failed to download kubectl"; exit 1; }
chmod +x ./kubectl && sudo mv ./kubectl /usr/local/bin/kubectl || { echo "Failed to move kubectl to /usr/local/bin"; exit 1; }

# Expose Kubernetes API server port
echo "Configuring firewall rules to expose Kubernetes API server..."
sudo ufw allow 6443/tcp || { echo "Failed to configure UFW for API server"; exit 1; }
sudo ufw allow 80/tcp || { echo "Failed to configure UFW for port 80"; exit 1; }  # Ensure port 80 is open

# Install kubelet and kubeadm
echo "Installing kubelet and kubeadm..."
curl -fsSL https://pkgs.k8s.io/core:/stable:/v${KUBERNETES_VERSION_SHORT}/deb/Release.key | sudo gpg --dearmor -o /etc/apt/keyrings/kubernetes-apt-keyring.gpg
echo "deb [signed-by=/etc/apt/keyrings/kubernetes-apt-keyring.gpg] https://pkgs.k8s.io/core:/stable:/v${KUBERNETES_VERSION_SHORT}/deb/ /" | sudo tee /etc/apt/sources.list.d/kubernetes.list
sudo apt-get update && sudo apt-get install -y kubelet kubeadm || { echo "Failed to install kubelet and kubeadm"; exit 1; }

# Enable and start kubelet service
sudo systemctl enable kubelet && sudo systemctl start kubelet || { echo "Failed to start kubelet"; exit 1; }

# Disable swap
echo "Disabling swap..."
sudo swapoff -a && sudo sed -i '/ swap / s/^/#/' /etc/fstab || { echo "Failed to disable swap"; exit 1; }

# Configure containerd
containerd config default | sed 's/SystemdCgroup = false/SystemdCgroup = true/' | sed 's/sandbox_image = "registry.k8s.io\/pause:3.8"/sandbox_image = "registry.k8s.io\/pause:3.10"/' | sudo tee /etc/containerd/config.toml
sudo systemctl restart containerd || { echo "Failed to restart containerd"; exit 1; }

# Restart kubelet
echo "Restarting kubelet..."
sudo systemctl daemon-reload && sudo systemctl restart kubelet || { echo "Failed to restart kubelet"; exit 1; }

# Initialize the Kubernetes master node
echo "Initializing Kubernetes master node..."
sudo kubeadm init --apiserver-advertise-address=$MASTER_IP --pod-network-cidr=10.244.0.0/16 > /tmp/kubeadm-init.log || { echo "Failed to initialize Kubernetes master node"; exit 1; }

# Set up kubeconfig
echo "Setting up kubeconfig for root user..."
sudo mkdir -p /root/.kube
sudo cp /etc/kubernetes/admin.conf /root/.kube/config
sudo chmod 600 /root/.kube/config || { echo "Failed to set up kubeconfig"; exit 1; }

# Remove taints from control plane node
echo "Removing taints from control plane node..."
kubectl taint nodes --all node-role.kubernetes.io/control-plane- || true

# Create master-node namespace
echo "Creating master-node namespace..."
kubectl create namespace master-node || { echo "Failed to create master-node namespace"; exit 1; }

# Install Flannel CNI
echo "Installing Flannel CNI for networking..."
kubectl apply -f https://raw.githubusercontent.com/coreos/flannel/master/Documentation/kube-flannel.yml || { echo "Failed to apply Flannel CNI"; exit 1; }

# Install Kubernetes Dashboard
echo "Installing Kubernetes Dashboard..."
kubectl apply -f https://raw.githubusercontent.com/kubernetes/dashboard/v2.7.0/aio/deploy/recommended.yaml || { echo "Failed to install Kubernetes Dashboard"; exit 1; }

# Install NGINX Ingress Controller
echo "Installing NGINX Ingress Controller..."
kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/main/deploy/static/provider/cloud/deploy.yaml || { echo "Failed to install NGINX Ingress Controller"; exit 1; }

# Patch the NGINX Ingress Controller deployment to use HostPort
echo "Configuring NGINX Ingress Controller to use HostPort..."
kubectl patch deployment ingress-nginx-controller -n ingress-nginx --patch '
{
  "spec": {
    "template": {
      "spec": {
        "containers": [
          {
            "name": "controller",
            "ports": [
              {
                "containerPort": 80,
                "hostPort": 80
              },
              {
                "containerPort": 443,
                "hostPort": 443
              }
            ]
          }
        ]
      }
    }
  }
}'

# Define and apply IngressClass
echo "Defining and applying IngressClass..."
kubectl apply -f - <<EOF
apiVersion: networking.k8s.io/v1
kind: IngressClass
metadata:
  name: nginx
  annotations:
    ingressclass.kubernetes.io/is-default-class: "true"
spec:
  controller: k8s.io/ingress-nginx
EOF

# Wait for NGINX Ingress Controller to be ready
echo "Waiting for NGINX Ingress Controller to be ready..."
sleep 20
kubectl wait --namespace ingress-nginx \
  --for=condition=ready pod \
  --selector=app.kubernetes.io/component=controller \
  --timeout=300s || { echo "NGINX Ingress Controller is not ready"; exit 1; }

# Check if the Ingress controller service is accessible
echo "Checking NGINX Ingress Controller service status..."
kubectl get svc -n ingress-nginx || { echo "Failed to get Ingress Controller service"; exit 1; }

# Clean up any old Ingress resources
echo "Cleaning up old Ingress resources..."
kubectl delete ingress http-redirect -n ingress-nginx || true
kubectl delete ingress kubernetes-dashboard-ingress -n kubernetes-dashboard || true

# Install cert-manager
echo "Installing cert-manager for Let's Encrypt..."
kubectl apply -f https://github.com/jetstack/cert-manager/releases/download/v1.12.0/cert-manager.yaml || { echo "Failed to install cert-manager"; exit 1; }

# Wait for cert-manager to be ready
echo "Waiting for cert-manager to be ready..."
sleep 20
kubectl wait --namespace cert-manager \
  --for=condition=ready pod \
  --selector=app.kubernetes.io/instance=cert-manager \
  --timeout=300s || { echo "cert-manager is not ready"; exit 1; }

# Create a ClusterIssuer for Let's Encrypt
echo "Creating Let's Encrypt ClusterIssuer..."
kubectl apply -f - <<EOF
apiVersion: cert-manager.io/v1
kind: ClusterIssuer
metadata:
  name: letsencrypt-production
spec:
  acme:
    server: https://acme-v02.api.letsencrypt.org/directory
    email: $EMAIL
    privateKeySecretRef:
      name: letsencrypt-production
    solvers:
    - http01:
        ingress:
          class: nginx
EOF

# Create an Ingress resource for the Dashboard with SSL
echo "Creating Ingress resource for Kubernetes Dashboard..."
kubectl apply -f - <<EOF
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: kubernetes-dashboard-ingress
  namespace: kubernetes-dashboard
  annotations:
    cert-manager.io/cluster-issuer: "letsencrypt-production"
    nginx.ingress.kubernetes.io/backend-protocol: "HTTPS"
    nginx.ingress.kubernetes.io/ssl-passthrough: "true"
spec:
  ingressClassName: nginx
  tls:
  - hosts:
    - $DOMAIN_NAME
    secretName: kubernetes-dashboard-cert
  rules:
  - host: $DOMAIN_NAME
    http:
      paths:
      - path: /
        pathType: Prefix
        backend:
          service:
            name: kubernetes-dashboard
            port:
              number: 443
EOF

# Wait for certificate to be issued
echo "Waiting for certificate to be issued..."
kubectl wait --for=condition=Ready certificate kubernetes-dashboard-cert -n kubernetes-dashboard --timeout=300s || { echo "Certificate was not issued in time"; exit 1; }

# Create Service Account and Cluster Role Binding for Dashboard
echo "Creating Service Account and Cluster Role Binding for Dashboard..."
kubectl create serviceaccount dashboard-admin-sa -n kube-system
kubectl create clusterrolebinding dashboard-admin-sa --clusterrole=cluster-admin --serviceaccount=kube-system:dashboard-admin-sa

# Create a secret to store the token for the service account
kubectl apply -f - <<EOF
apiVersion: v1
kind: Secret
metadata:
  name: dashboard-admin-sa-token
  namespace: kube-system
  annotations:
    kubernetes.io/service-account.name: dashboard-admin-sa
type: kubernetes.io/service-account-token
EOF

# Retrieve the token from the created secret
DASHBOARD_TOKEN=$(kubectl -n kube-system get secret dashboard-admin-sa-token -o jsonpath="{.data.token}" | base64 --decode)

if [ -z "$DASHBOARD_TOKEN" ]; then
  echo "Failed to retrieve Dashboard token. Please check the secret creation."
  exit 1;
fi

echo "Kubernetes Dashboard Access Token: $DASHBOARD_TOKEN"

# Display the Kubernetes master initialization details
echo "Kubernetes master node setup completed."
echo "To join worker nodes to the cluster, use the following command from your master node:"
grep 'kubeadm join' /tmp/kubeadm-init.log

# Print completion message
echo "Setup complete. You may now access the Kubernetes API server from the internet (e.g., using kubectl)."

# Provide instructions for accessing the Dashboard
echo -e "\nKubernetes Dashboard setup completed."
echo "To access the Kubernetes Dashboard, navigate to https://$DOMAIN_NAME/"
echo "Use the following token for login:"
echo $DASHBOARD_TOKEN
