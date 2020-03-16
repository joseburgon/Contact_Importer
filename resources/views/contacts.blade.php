@extends('layouts.app')

@section('content')
          
<div class="container">
    <div class="row">
        @if(session()->has('failures'))  
        <div class="col">
            <div class="alert alert-warning" role="alert">
                There were some contacts that could not be imported.
            </div>
            <ul class="list-group list-group-flush">
            @foreach(session()->get('failures') as $failure)                        
                <li class="list-group-item disabled">Problem with contact in row: {{ $failure['row'] }}</li>
                <li class="list-group-item">{{ $failure['error'][0] }}</li>                
            @endforeach
            </ul>
        </div>
        @endif
        @if(session()->has('message'))
        <div class="col">
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        </div>
        @endif
        @if(session()->has('failed'))
        <div class="col">
            <div class="alert alert-danger">
                {{ session()->get('failed') }}
            </div>
        </div>
        @endif
    </div>
</div>

<div class="container">
    <div class="row">
      <div class="col">
          <div class="card-body">
            <h2>My Contacts List</h2>
              <table class="table table-striped table-hover">
                  <thead class="thead-dark">
                      <tr>
                          <th>Name</th>
                          <th>Birthday</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Card</th>
                          <th>Card Brand</th>
                          <th>Email</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if ($data ?? '')
                      @foreach ($data as $row)
                          <tr>
                              <td>{{ $row->name }}</td>
                              <td>{{ $row->birthday = date('Y F d') }}</td>
                              <td>{{ $row->phone }}</td>
                              <td>{{ $row->address }}</td>
                              <td>{{ $row->card }}</td>
                              <td>{{ $row->card_brand }}</td>
                              <td>{{ $row->email }}</td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
              {{ $data->render() }}
              @endif
          </div>
      </div>
    </div>
    <div class="card bg-light">
      <div class="card-header">
          Import CSV File
      </div>
      <div class="card-body">
          <div class="col">
            <form action="{{ route('import_parse') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                  <div class="col-md-6">
                      <input id="csv_file" type="file" class="form-control" name="csv_file" required>
  
                      @if ($errors->has('csv_file'))
                          <span class="help-block">
                          <strong>{{ $errors->first('csv_file') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>
  
              <div class="form-group">
                  <div class="col-md-6 col-md-offset-4">
                      <div class="checkbox">
                          <label>
                              <input type="checkbox" name="header" checked> File contains header row?
                          </label>
                      </div>
                  </div>
              </div>
  
              <div class="form-group">
                  <div class="col">
                      <button type="submit" class="btn btn-primary">
                          Parse CSV
                      </button>
                      <a href="{{ route('files') }}" class="btn btn-dark" role="button">
                        Imported Files
                      </a>
                  </div>
              </div>
            </form>
          </div>
      </div>
  </div>
</div>
    
@endsection