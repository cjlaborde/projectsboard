@extends ('layouts.app')

@section('content')
    <div style="display: flex; align-items: center;">
        <h1 style="margin-right: auto;">Birdboard</h1>
        <a href="/projects/create">New Project</a>
    </div>

    <ul>
        @forelse($projects as $project)
            <li>
                <a href="{{ $project->path() }}">{{ $project->title }}</a>
            </li>
        @empty
                <li>No projects yet.</li>
        @endforelse
    </ul>
@endsection