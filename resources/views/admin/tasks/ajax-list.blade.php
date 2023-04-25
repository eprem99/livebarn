@forelse($taskFiles as $file)
    @php 
        $ext = pathinfo($file->hashname, PATHINFO_EXTENSION); 
    @endphp
    <li class="list-group-item" id="task-file-{{  $file->id }}">
        <div class="row">
            <div class="col-md-1">
                <input class="form-control mr-2" type="checkbox" name="images[]" value="{{ $file->file_url }}">
             </div>
            <div class="col-md-1">
                @if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
                    <a target="_blank" href="{{ $file->file_url }}"><img src="{{ $file->file_url }}" alt="{{ $file->filename }}" style="width: 40px; height: 40px;"/></a>
                @else
                    <a target="_blank" href="{{ $file->file_url }}"><img src="/img/Icon_pdf_file.png" alt="{{ $file->filename }}" style="width: 40px; height: 40px;"/></a>
                @endif 
            </div>
            <div class="col-md-4">
                {{ $file->filename }}
            </div>
            <div class="col-md-3">
                <span class="">{{ ucfirst($file->created_at->timezone($global->timezone)->format($global->date_format)) }}  {{ ucfirst($file->created_at->timezone($global->timezone)->format($global->time_format)) }}</span>
            </div>
            <div class="col-md-3">
                <a target="_blank" href="{{ $file->file_url }}"
                   data-toggle="tooltip" data-original-title="View"
                   class="btn btn-info btn-circle"><i
                            class="fa fa-search"></i></a>
                @if(is_null($file->external_link))
                    <a href="{{ route('admin.task-files.download', $file->id) }}"
                       data-toggle="tooltip" data-original-title="Download"
                       class="btn btn-inverse btn-circle"><i
                                class="fa fa-download"></i></a>
                @endif
                <a href="javascript:;" data-toggle="tooltip" data-original-title="Edit" data-task-id="{{ $file->task_id }}" data-file-id="{{ $file->id }}"
                                           data-pk="list" class="btn btn-warning btn-circle file-edit"><i class="fa fa-edit"></i></a>

                <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}"
                   data-pk="list" class="btn btn-danger btn-circle file-delete"><i class="fa fa-times"></i></a>

            </div>
            <div id="fileinput_{{ $file->id }}" class="col-md-12" style="display:none;">
                <div class="col-md-10">
                    <input class="form-control" type="text" name="file-name" value="{{ $file->filename }}"/>
                </div>
                <div class="col-md-2">
                <a href="javascript:;" id="filebutton_{{ $file->id }}" data-toggle="tooltip" data-original-title="Save" data-file-id="{{ $file->id }}"
                                           data-pk="list" class="btn btn-success file-save"><i class="fa fa-check"></i></a> 
                </div>
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-10">
                @lang('messages.noFileUploaded')
            </div>
        </div>
    </li>
@endforelse
