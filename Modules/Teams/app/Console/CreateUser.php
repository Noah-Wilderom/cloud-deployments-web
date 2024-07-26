<?php

namespace Modules\Teams\Console;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use function Laravel\Prompts\form;
use function Laravel\Prompts\outro;

class CreateUser extends Command
{
    use PasswordValidationRules;
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'management:create-user';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userForm = form()
            ->text("Name", required: true, name: "name")
            ->text("Email", required: true, validate: ["email" => "required|unique:users,email"], name: "email")
            ->password(
                'Password',
                validate: ['password' => $this->passwordRules(confirm: false)],
                name: 'password',
            )
            ->select("Role", options: Role::all()->pluck("name", "id"), name: "role")
            ->confirm("Create user?")
            ->submit();

        $user = User::create([
            "name" => $userForm["name"],
            "email" => $userForm["email"],
            "password" => Hash::make($userForm["name"]),
        ]);

        $user->syncRoles([$userForm["role"]]);

        outro(sprintf("User %s has been created", $user->email));
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
        ];
    }
}
