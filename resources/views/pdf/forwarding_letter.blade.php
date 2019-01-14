<!DOCTYPE html>
<html>
<head>
    <link href="{{asset('css/
    ')}}" rel="stylesheet">
    <style>

    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size:12px;
    }
    .head {
        font-size:12px;
        font-weight: bold;
        text-align:center;
        font-family: Calibri, Arial, Helvetica, sans-serif; 
    }
    .main-title
    {
        font-family: Calibri, Arial, Helvetica, sans-serif; 
    }

    .spacing 
    {
        word-spacing: 2px;
    }
    span
    {
        margin-top: 50px;
        float:right;
        font-weight: bold;
    }
    .signature{
        text-align: right;
        margin-top: 80px;
        font-family: Calibri, Arial, Helvetica, sans-serif; 
    }
    .signature p {
        margin-bottom: 0;
        margin-top: 12px;
        font-family: Calibri, Arial, Helvetica, sans-serif; 
    }

</style>
</head>
<body>

    <header style="text-align: center;">
     Forwarding Letter of GAR 
 </header>
 <div style='margin-top:20px;'>
   <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:0;">
    <tbody>
        <tr>
            <td width="65%" style="border:0;">
                &nbsp;&nbsp;&nbsp;
            </td>
            <td width="45%" style="border:0;">
                <table align="left" cellpadding="0" cellspacing="0" style="border:0;">
                    <tbody>
                        <tr>
                            <td style="border:0; text-align:center;font-family: arial;color: #000000; line-height:1.5;font-size: 16px; padding: 10px 0px; text-decoration: none;">
                                Bangla Sahib Road
                                <br> Behind kali Mandir, Gole Market
                                <br> New Delhi-110001
                                <br> Dated: 27/11/2018
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:0;">
    <tbody>
        <tr>
            <!-- <td width="10%" style="border:0;"> &nbsp;&nbsp;&nbsp;</td> -->
            <td width="30%" style="border:0;">
                <table align="left" cellpadding="0" cellspacing="0" style="border:0;">
                    <tbody>
                        <tr>
                            <td style="border:0; font-family: arial;color: #000000; line-height:1.5;font-size: 16px; padding: 10px 0px; text-decoration: none;">
                                To,

                            </td>
                        </tr>
                        <tr>
                            <td style="border:0;font-family: arial;color: #000000; line-height:1.5;font-size: 16px; padding: 10px 0px; text-decoration: none;">
                                The Manager
                                <br> Parliment Street
                                <br> Jeeven Bharti Building
                                <br> New Delhi

                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
            <td width="60%" style="border:0;">
                &nbsp;&nbsp;&nbsp;
            </td>

        </tr>
    </tbody>
</table>

<table width=100%  cellpadding="6" style="margin-top: 150px;">

    <thead>
        <tr>
            <td class="head">SR NO.</td>
            <td class="head">Cheque Date</td>
            <td class="head">Cheque NO.</td>
            <td class="head">Name & Designation/ Party</td>
            <td class="head">Bank A/C</td>
            <td class="head">Name of the Bank & Branch</td>
            <td class="head">IFSC Code No</td>
            <td class="head">Amount (in Rs.)</td>
        </tr>
    </thead>
    <tbody>
        <?php $counter = 1;

        ?>
        <tr>
           @if(isset($data['result']) && !empty($data['result']))
           @foreach($data['result'] as $item)

           <td>{{$counter++}} </td>
           <td>{{isset($item['cheque_date']) ? (date('d/m/Y',strtotime($item['cheque_date']))) : ''}}   
               <td>{{isset($item['cheque_number']) ? $item['cheque_number'] : ''}} </td>
               <td>{{isset($item['vendor_name']) ? $item['vendor_name'] : '' }}</td>
               <td>{{isset($item['bank_acc_no']) ? $item['bank_acc_no'] : '' }}</td>
               <td>{{isset($item['bank_name']) ? $item['bank_name'] : '' }} - {{isset($item['bank_branch']) ? $item['bank_branch'] : '' }} </td>
               <td>{{isset($item['ifsc_code']) ? $item['ifsc_code'] : '' }} </td>
               <td>{{isset($item['cheque_amount']) ? $item['cheque_amount'] : '' }} </td>
           </tr>
           @endforeach
           @endif
           <tr>
            <td colspan="6">&nbsp;</td>
            <td>Total: </td>
            <td>{{isset($data['total_amount']) ? $data['total_amount'] : '' }}</td>
        </tr>
    </tbody>
</table>
</div>   
</body>
</html>

<?php //exit; ?>
