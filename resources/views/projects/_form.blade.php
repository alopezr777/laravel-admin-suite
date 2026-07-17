<div class="form-grid">
    <div class="form-group">
        <label class="form-label" for="name">Project name</label>
        <input class="form-control" id="name" name="name" value="{{ old('name', $project->name ?? '') }}" required maxlength="120">
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="client">Client</label>
        <input class="form-control" id="client" name="client" value="{{ old('client', $project->client ?? '') }}" required maxlength="120">
        @error('client')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="manager_id">Project manager</label>
        <select class="form-select" id="manager_id" name="manager_id">
            <option value="">Unassigned</option>
            @foreach($managers as $manager)
                <option value="{{ $manager->id }}" @selected((string) old('manager_id', $project->manager_id ?? '') === (string) $manager->id)>{{ $manager->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="budget">Budget (€)</label>
        <input class="form-control" id="budget" name="budget" type="number" min="0" step="0.01" value="{{ old('budget', $project->budget ?? 0) }}" required>
    </div>
    <div class="form-group">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status" required>
            @foreach(['planning' => 'Planning', 'active' => 'Active', 'on_hold' => 'On hold', 'completed' => 'Completed'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $project->status ?? 'planning') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="priority">Priority</label>
        <select class="form-select" id="priority" name="priority" required>
            @foreach(['low', 'medium', 'high'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $project->priority ?? 'medium') === $priority)>{{ ucfirst($priority) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="starts_at">Start date</label>
        <input class="form-control" id="starts_at" name="starts_at" type="date" value="{{ old('starts_at', isset($project) ? $project->starts_at?->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group">
        <label class="form-label" for="due_at">Due date</label>
        <input class="form-control" id="due_at" name="due_at" type="date" value="{{ old('due_at', isset($project) ? $project->due_at?->format('Y-m-d') : '') }}">
    </div>
    <div class="form-group full">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description" maxlength="3000">{{ old('description', $project->description ?? '') }}</textarea>
        <span class="form-help">Briefly describe the goals, scope and expected outcome.</span>
    </div>
</div>
<div class="form-actions">
    <a class="btn btn-secondary" href="{{ isset($project) ? route('projects.show', $project) : route('projects.index') }}">Cancel</a>
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
</div>

