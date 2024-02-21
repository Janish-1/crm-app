@extends('layouts.app')

@section('sidecontent')
<h1 style="margin-left: 30px">Career Applications</h1>
<section id="careertable">
    <div class="center-table">
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">ID</th>
                    <th style="width: 120px;">Name</th>
                    <th style="width: 120px;">Email</th>
                    <th style="width: 120px;">Contact Number</th>
                    <th style="width: 100px;">Category</th>
                    <th style="width: 100px;">Experience</th>
                    <th style="width: 100px;">CV</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 120px;">Applied To</th>
                    <th style="width: 80px;">Test Status</th>
                    <th style="width: 80px;">Test ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($careers as $career)
                <tr>
                    <td>{{ $career->id }}</td>
                    <td>{{ $career->name }}</td>
                    <td>{{ $career->mail }}</td>
                    <td>{{ $career->contactnumber }}</td>
                    <td>{{ $career->category }}</td>
                    <td>{{ $career->experience }}</td>
                    <td>
                        <a href="{{ asset(Storage::url($career->cv)) }}" target="_blank">View CV</a>
                        <a href="{{ asset(Storage::url($career->cv)) }}" target="_blank" download>Download CV</a>
                    </td>
                    <td>{{ $career->status }}</td>
                    <td>{{ $career->appliedto }}</td>
                    <td>{{ $career->teststatus }}</td>
                    <td class="truncate-text" style="width: 80px;">{{ $career->testid }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection