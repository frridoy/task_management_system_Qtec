<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_list_page_loads(): void
    {
        $this->get(route('tasks.index'))->assertOk();
    }

    public function test_task_list_shows_tasks(): void
    {
        Task::create(['title' => 'Test Task', 'status' => 'pending', 'priority' => 'medium']);

        $this->get(route('tasks.index'))->assertSee('Test Task');
    }

    public function test_filter_by_title(): void
    {
        Task::create(['title' => 'Alpha Task', 'status' => 'pending', 'priority' => 'low']);
        Task::create(['title' => 'Beta Task',  'status' => 'pending', 'priority' => 'low']);

        $this->get(route('tasks.index', ['title' => 'Alpha']))
            ->assertSee('Alpha Task')
            ->assertDontSee('Beta Task');
    }

    public function test_filter_by_status(): void
    {
        Task::create(['title' => 'Pending Task',   'status' => 'pending',   'priority' => 'low']);
        Task::create(['title' => 'Completed Task', 'status' => 'completed', 'priority' => 'low']);

        $this->get(route('tasks.index', ['status' => 'pending']))
            ->assertSee('Pending Task')
            ->assertDontSee('Completed Task');
    }

    public function test_filter_by_priority(): void
    {
        Task::create(['title' => 'High Task', 'status' => 'pending', 'priority' => 'high']);
        Task::create(['title' => 'Low Task',  'status' => 'pending', 'priority' => 'low']);

        $this->get(route('tasks.index', ['priority' => 'high']))
            ->assertSee('High Task')
            ->assertDontSee('Low Task');
    }

    public function test_filter_by_date_range(): void
    {
        Task::create(['title' => 'Due Soon', 'status' => 'pending', 'priority' => 'low', 'due_date' => '2030-01-10']);
        Task::create(['title' => 'Due Later', 'status' => 'pending', 'priority' => 'low', 'due_date' => '2030-06-01']);

        $this->get(route('tasks.index', ['date_from' => '2030-01-01', 'date_to' => '2030-01-31']))
            ->assertSee('Due Soon')
            ->assertDontSee('Due Later');
    }

    // create
    public function test_create_page_loads(): void
    {
        $this->get(route('tasks.create'))->assertOk();
    }

    //store
    public function test_store_creates_task(): void
    {
        $this->post(route('tasks.store'), [
            'title'    => 'New Task',
            'status'   => 'pending',
            'priority' => 'medium',
        ])->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_store_fails_without_title(): void
    {
        $this->post(route('tasks.store'), [
            'status'   => 'pending',
            'priority' => 'medium',
        ])->assertSessionHasErrors('title');
    }

    public function test_store_fails_with_title_exceeding_100_chars(): void
    {
        $this->post(route('tasks.store'), [
            'title'    => str_repeat('a', 101),
            'status'   => 'pending',
            'priority' => 'medium',
        ])->assertSessionHasErrors('title');
    }

    public function test_store_fails_with_past_due_date(): void
    {
        $this->post(route('tasks.store'), [
            'title'    => 'Task',
            'status'   => 'pending',
            'priority' => 'medium',
            'due_date' => '2000-01-01',
        ])->assertSessionHasErrors('due_date');
    }

    //edit
    public function test_edit_returns_task_json(): void
    {
        $task = Task::create(['title' => 'Edit Me', 'status' => 'pending', 'priority' => 'low']);

        $this->get(route('tasks.edit', $task))
            ->assertOk()
            ->assertJson(['title' => 'Edit Me']);
    }

    //update
    public function test_update_modifies_task(): void
    {
        $task = Task::create(['title' => 'Old Title', 'status' => 'pending', 'priority' => 'low']);

        $this->put(route('tasks.update', $task), [
            'title'    => 'New Title',
            'status'   => 'completed',
            'priority' => 'high',
        ])->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'New Title', 'status' => 'completed']);
    }

    public function test_update_fails_without_title(): void
    {
        $task = Task::create(['title' => 'Task', 'status' => 'pending', 'priority' => 'low']);

        $this->put(route('tasks.update', $task), [
            'status'   => 'pending',
            'priority' => 'low',
        ])->assertSessionHasErrors('title');
    }

    //softdelete
    public function test_soft_delete_sets_deleted_at(): void
    {
        $task = Task::create(['title' => 'Delete Me', 'status' => 'pending', 'priority' => 'low']);

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_soft_deleted_task_not_visible_in_list(): void
    {
        $task = Task::create(['title' => 'Hidden Task', 'status' => 'pending', 'priority' => 'low']);
        $task->delete();

        $this->get(route('tasks.index'))->assertDontSee('Hidden Task');
    }
}
