@if($students->isEmpty())
    <div style="text-align:center;padding:28px;color:#94A3B8;">
        No students found in this list.
    </div>
@else
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Roll Number</th>
                    <th>Student Name</th>
                    <th>Attendance</th>
                    <th>Feedback Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $attendance)
                    <tr>
                        <td>
                            {{ $attendance->student?->roll_number ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $attendance->student?->name ?? 'Unknown Student' }}
                        </td>

                        <td>
                            <span class="badge badge-{{ in_array($attendance->status, ['present', 'late']) ? 'green' : 'red' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>

                        <td>
                            @if($attendance->feedback_enabled)
                                <span class="badge badge-blue">
                                    Enabled
                                </span>
                            @else
                                <span class="badge badge-gray">
                                    Not Enabled
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif