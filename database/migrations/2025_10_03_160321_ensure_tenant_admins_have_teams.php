<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tenantAdmins = User::where('is_tenant_admin', true)->get();
        
        foreach ($tenantAdmins as $admin) {
            // Check if user already has any teams
            if ($admin->ownedTeams()->count() === 0) {
                // Create a personal team for the tenant admin
                $team = $admin->ownedTeams()->create([
                    'name' => $admin->name . "'s Team",
                    'personal_team' => true,
                ]);
                
                // Add the user to the team
                $admin->teams()->attach($team, ['role' => 'admin']);
                
                // Set the current team
                $admin->current_team_id = $team->id;
                $admin->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // This is a data migration, so we don't need to do anything in the down method
        // as we don't want to delete teams that might have been created manually
    }
};
