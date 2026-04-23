<table class="table table-hover table-dark">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Combinacion</th>
            <th colspan="3" scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($activeAssignments as $assignment)
        <tr>
            <td>{{ $assignment->id }}</td>
            <td>{{ $assignment->itemSummary() }}</td>
            <td>
                <a class="btn btn-icon btn-primary btn-sm" href="{{route('assignments.show', $assignment)}}">
                    <i class="ni ni-send text-dark"></i>
                </a>
            </td>
            <td>
                <a class="btn btn-icon btn-info btn-sm" href="{{route('assignments.edit', $assignment)}}">
                    <i class="ni ni-ruler-pencil text-white"></i>
                </a>
            </td>
            <td>
                <form action="{{ route('assignments.delete', $assignment) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta asignacion?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon btn-danger btn-sm">
                        <i class="ni ni-fat-remove text-white"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$activeAssignments->links()}}
