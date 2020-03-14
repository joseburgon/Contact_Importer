@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
      <div class="col">
          <div class="card-body">
            <h2>Imported Files</h2>
              <table class="table table-striped table-hover">
                  <thead class="thead-dark">
                      <tr>
                          <th>File Name</th>
                          <th>Status</th>
                          <th>Download File</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if ($files ?? '')
                      
                      @foreach ($files as $file)
                          <tr>
                              <td>{{ $file->file_name }}</td>
                              <td>{{ $file->status }}</td>
                              <td>
                                <a id="download" href="{{ '/download_file/'.$file->id }}" class="btn btn-sm btn-info">
                                  Download
                                </a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
              {{ $files->render() }}
              @endif
          </div>
      </div>
    </div>
@endsection