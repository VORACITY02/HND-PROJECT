<?php

use App\Models\User;

it('requires admin role for supervision management page', function () {
    $student = User::factory()->create(['role' => 'user']);

    $this->actingAs($student)
        ->get(route('admin.supervision.manage.index'))
        ->assertStatus(403);
});
