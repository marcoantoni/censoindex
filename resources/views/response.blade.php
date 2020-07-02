@extends('app')

@section('title', 'Resposta')

@section('sidebar')
@parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
  <table>
    <thead>
      <tr>
        <th>Escola</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($escolas as $escola)
      <tr>
        <td>{{ $escola->NO_ENTIDADE }}</td>
    </tr>     
    @endforeach
  </tbody>
  </table>

{!! $debub !!}
@endsection