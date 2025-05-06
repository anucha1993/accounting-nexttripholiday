@php
    // เมื่อถูกแทรกจาก JS จะส่ง item เป็น JSON string ชั่วคราวก่อน replace
    $item = $item ?: json_decode('__JSON__');
@endphp
<tr data-id="{{ $item->id }}">
    <td>{{ $item->id }}</td>
    <td>{{ $item->name }}</td>
    <td>{{ $item->type }}</td>
    <td>

        @if ($item->min_profit && $item->min_profit <= 0  )
           <span class="text-danger"> 0.00 </span>
        @else
        {{ number_format($item->min_profit, 2) }}
        @endif
       </td>
       <td>

        @if ($item->min_profit && $item->min_profit <= 0  )
           <
        @elseif ($item->max_profit === NULL && $item->max_profit <= 0  )
          >
        @else
          -
        @endif
       </td>

    <td>
        
        @if ($item->max_profit === NULL && $item->max_profit <= 0  )
        <span class="text-success">  0.00 </span>  
        @else
        {{ number_format($item->max_profit, 2) }}
        @endif
         </td>
    <td>
        @if ($item->type === 'step')
        {{ number_format($item->value, 2) }} บาท/คน
        @else
        {{ number_format($item->value).'%' }} ของยอดทั้งหมด
            
        @endif
        
       </td>
    <td>{{ $item->unit }}</td>
    <td>
        @if($item->status==='enable')
            <span class="badge bg-success">Enable</span>
        @else
            <span class="badge bg-secondary">Disable</span>
        @endif
    </td>
    <td>
        <button class="btn btn-sm btn-warning btn-edit"
                data-item='@json($item)'>แก้ไข</button>
        <button class="btn btn-sm btn-danger btn-delete"
                data-id="{{ $item->id }}">ลบ</button>
    </td>
</tr>
