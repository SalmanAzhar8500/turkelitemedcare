@extends('admin.layouts.app')
@section('title', 'Contact Messages')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix mb-3">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card"><div class="body"><h6>Total Messages</h6><h3>{{ $stats['total'] }}</h3></div></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card"><div class="body"><h6>Today Messages</h6><h3>{{ $stats['today'] }}</h3></div></div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header"><h2><strong>Contact</strong> Messages</h2></div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Submitted</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($messages as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->message), 120) }}</td>
                                    <td>{{ $item->created_at?->format('d M Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No contact messages found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $messages->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
