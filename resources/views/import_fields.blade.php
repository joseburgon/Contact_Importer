@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row">
        <form class="form-horizontal" method="POST" action="{{ route('import_process') }}">
            @csrf
            <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />

            <table class="table">
                @if (isset($csv_header_fields))
                    <tr>
                        @foreach ($csv_header_fields as $csv_header_field)
                            <th>{{ $csv_header_field }}</th>
                        @endforeach
                    </tr>
                    <input type="hidden" name="startRow" value="2" />
                @else
                    <input type="hidden" name="startRow" value="1" />
                @endif
                @foreach ($csv_data as $row)
                    <tr>
                    @foreach ($row as $key => $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    </tr>
                @endforeach
                <tr>
                    @foreach ($csv_data[0] as $key => $value)
                        <td>
                            <select name="fields[{{ $key }}]">
                                @foreach (config('app.db_fields') as $index => $db_field)
                                    <option value="{{ $loop->index }}"
                                        @if ($key === $index) selected @endif>{{ $db_field }}</option>
                                @endforeach
                            </select>
                        </td>
                    @endforeach
                </tr>
            </table>

            <button type="submit" class="btn btn-primary">
                Import Data
            </button>
        </form>
    </div>
</div>
@endsection