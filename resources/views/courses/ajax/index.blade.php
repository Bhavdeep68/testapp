@if(!$courses->isEmpty())
    @foreach($courses as $key=>$value)

        <tr>
            <td>{{$value->coach->name}}</td>
            <td>{{$value->name}}</td>
            <td>{{$value->description}} </td>
            <td>{{$value->duration}} </td>
            <td>{{$value->fees}} </td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    
                    <a href="{{ url('courses/edit/'.$value->course_id) }}"><i class="fa fa-pencil m-r-5"></i></a>&nbsp;&nbsp;
                    <a class="text-danger" href="javascript:void(0);" onclick="deleteEntity({{ $value->course_id }});"><i class="fa fa-trash-o m-r-5"></i> </a>
               
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">
            <h4 class="m-0">No courses found!</h4>
        </td>
    </tr>
@endif
<tr id="ajaxpagingdata">
    <td>
        {!! $courses->render() !!}
    </td>
</tr>
