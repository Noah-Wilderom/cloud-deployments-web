<?php

namespace Modules\Cloud\Http\Controllers;

use App\Enums\IntegrationProvider;
use App\SSH\KeyPairGenerator;
use Github\Client;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;
use Modules\Cloud\Data\GitRepositoryData;
use Modules\Cloud\Data\ProjectSettings;
use Modules\Cloud\Enums\ProjectEnvironment;
use Modules\Cloud\Enums\ProjectTemplate;
use Modules\Cloud\Http\Requests\StoreProjectRequest;
use Modules\Cloud\Jobs\ProvisionProject;
use Modules\Cloud\Jobs\ProvisionServer;
use Modules\Cloud\Models\Project;
use Modules\Cloud\Models\Server;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Domain;

class ProjectController extends Controller
{
    public function index(Request $request): InertiaResponse {
        $user = $request->user();
        $repositories = cache()->remember(sprintf("user-repositories.%s", $request->user()->getKey()), 300, function () use ($user) {
            $client = IntegrationProvider::Github->getIntegrationService($user)
                ->service();
            $paginator = new \Github\ResultPager($client);
            return $paginator->fetchAll($client->currentUser(), "repositories", [
                "sort" => "created",
                "direction" => "desc",
            ]);
        });

        $projects = Project::all();
        $projects->loadMissing(["server", "domain", "customer"]);

        return inertia("Cloud::Projects/Index", [
            "projects" => $projects,
            "servers" => Server::all(),
            "customers" => Customer::all(),
            "domains" => Domain::all(),
            "templates" => ProjectTemplate::getAllByDisplayName(),
            "repositories" => $repositories,
            "randomName" => generate_random_name(),
        ]);
    }

    public function show(Project $project): InertiaResponse {
        return inertia("Projects/Show", [
            "project" => $project,
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse {
        $gitRepository = GitRepositoryData::newFromGithub($request->user(), $request->get("git_repository"));
        if(! $gitRepository) {
            return back()
                ->with("notifications", [
                    "type" => "error",
                    "title" => "Project failed",
                    "description" => "No git repository found",
                ]);
        }

        $sshCredentialsDir = uuidV7();
        $sshPrivKeyPath = sprintf("%s/id_rsa", $sshCredentialsDir);
        $sshPubKeyPath = sprintf("%s/id_rsa.pub", $sshCredentialsDir);

        $hostSshCredentialsDir = uuidV7();
        $hostSshPrivKeyPath = sprintf("%s/id_rsa", $hostSshCredentialsDir);
        $hostSshPubKeyPath = sprintf("%s/id_rsa.pub", $hostSshCredentialsDir);

        (new KeyPairGenerator())
            ->generateKeyPair($sshPrivKeyPath, $sshPubKeyPath);
        (new KeyPairGenerator(keyComment: sprintf("%s@cloud-deployments.dev", $request->get("name"))))
            ->generateKeyPair($hostSshPrivKeyPath, $hostSshPubKeyPath);

        $project = Project::create([
            "user_id" => $request->user()->getKey(),
            "customer_id" => $request->get("customer_id"),
            "domain_id" => $request->get("domain_id"),
            "server_id" => $request->get("server_id"),
            "initialized" => false,
            "sub_domain" => $request->get("sub_domain"),
            "template" => $request->get("template"),
            "name" => $request->get("name"),
            "ssh_user" => Project::generateProjectName($request->get("name")),
            "ssh_credentials_path" => $sshCredentialsDir,
            "host_ssh_credentials_path" => $hostSshCredentialsDir,
            "git_repository" => $gitRepository,
            "environments" => ProjectEnvironment::defaultEnvironments(),
            "settings" => new ProjectSettings(),
        ]);

        ProvisionProject::dispatch($project);

        return back()
            ->with("notifications", [
                "type" => "success",
                "title" => "Project is created",
                "description" => "Project is successfully created",
            ]);
    }
}
