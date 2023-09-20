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
                    
                    <a href="{{ url('enroll/'.$value->course_id) }}">Enroll Course</a>
               
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
