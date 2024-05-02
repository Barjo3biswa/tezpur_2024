<table class="table table-bordered">
    <caption><h3>Extra uploaded Documents of : {{$application->application_no}}</h3></caption>
    <thead>
        <tr>
            <th>#</th>
            <th>Doc Name</th>
            <th>Remark</th>
            <th>File Type</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        <tbody>
            @forelse ($application->extra_documents as $index => $document)
                <tr>
                    <td>{{$index +1}}</td>
                    <td>{{$document->doc_name}}</td>
                    <td>{{$document->remark}}</td>
                    <td>{{$document->mime_type}}</td>
                    <td>
                        <a href="{{route("common.download.image", [$application->student_id, $application->id, $document->file_name])}}" target="_blank">
                            <button type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-eye-open"></i> View</button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Documents not found.</td>
                </tr>
            @endforelse
        </tbody>
    </tbody>
</table>