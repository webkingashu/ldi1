<!doctype html>
<html>
<head>
  <?php //print_r($data['email_users']); exit;?>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
  <table width="100%" align="center" cellpadding="0" cellspacing="0" >
    <tbody>
      <tr>
        <td colspan="3"  align="center" valign="top" style="font-family: arial;color: #000000; font-size: 18px; padding-top: 10px; text-decoration: none; font-weight: bold;"><strong>File No.: {{isset($data['result']['file_number']) ?$data['result']['file_number'] : ''}}</strong></td>
      </tr>
      <tr>
        <td colspan="3"  align="center" valign="top" style="font-family: arial;color: #000000; font-size: 18px; padding-top: 10px; text-decoration: none; font-weight: bold;"><strong>Government of India</strong><br>
          <strong>Ministry of Electronics &amp; Information Technology (MeitY)</strong>
          <br>
          <strong>Unique Identification Authority of India (UIDAI)</strong></td>
        </tr>
        <tr>
          <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><table  border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td>
                  <table  width="100%" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td  align="left" valign="top" style="width:300px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>Address:</strong> {{isset($data['result']['office_address']) ?$data['result']['office_address'] : ''}}
                      </td>
                      <td style="width:70%;">&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                  </table>
                </td>
                
                </tr>
                <tr>
                <td  align="left" valign="top" style="padding-top:15px; font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>Serial No:</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family: arial;color: #000000; font-size: 16px;font-weight: normal; padding-top:15px;"><strong>Wing:</strong> {{isset($data['result']['department_name']) ?$data['result']['department_name'] : ''}}</td>
                </tr>
                <tr>
                <td align="left" valign="top" style="font-family: arial;color: #000000; font-size: 16px;font-weight: normal;padding-top:15px;"><strong>Date:</strong><?php echo date("d/m/Y");?></td>
              </tr>
            </tbody>
          </table></td>
        </tr>
        <tr>
          <td colspan="3" align="center" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 18px;font-weight: normal;"><strong><u>RELEASE ORDER</u></strong></td>
        </tr>
        <tr>
          <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">Sanction of the  Competent Authority (CEO, UIDAI) was accorded for <strong><?php echo isset($data['result']['ro_title']) ? $data['result']['ro_title']: ''; ?> </strong>for a  period of <strong><?php echo isset($data['result']['period']) ? $data['result']['period']: ''; ?></strong> with <strong><?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></strong> amounting to <strong> <?php echo isset($data['result']['sanction_total']) ? $data['result']['sanction_total']: ''; ?> </strong>(<?php echo isset($data['result']['sanction_total_in_word']) ? $data['result']['sanction_total_in_word']: ''; ?>) (exclusive of taxes) with the concurrence of DDG (l;) vide FA <strong><?php echo isset($data['result']['fc_number']) ? $data['result']['fc_number']: ''; ?><?php echo isset($data['result']['fa_date']) ? $data['result']['fa_date']: ''; ?></strong></td>
        </tr>
        <tr>
          <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">In pursuance of the  above, sanction of <strong>DDG ({{isset($data['result']['department_name']) ?$data['result']['department_name'] : ''}})</strong> is hereby conveyed for payment of an  amount of <strong><?php echo isset($data['result']['release_order_amount']) ? $data['result']['release_order_amount']: ''; ?>/- (<?php echo isset($data['result']['release_order_amount_in_word']) ? $data['result']['release_order_amount_in_word']: ''; ?>)</strong> to <strong><?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></strong> for <strong><?php echo isset($data['result']['ro_title']) ? $data['result']['ro_title']: ''; ?></strong> for the period of <strong>____________.</strong></td>
        </tr>
      @if(isset($data['result']['invoice_details']) && count($data['result']['invoice_details']) >0)    
        <tr>
          <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>EAS utilized:</strong></td>
        </tr>
        <tr>
          <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000000;">
            <tbody>
              <tr>
                <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px; border-right: 1px solid #000;">Sr No</th>
                <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px; border-right: 1px solid #000;"><strong>Invoice No.</strong></th>
                <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: bold;border-right: 1px solid #000; padding:10px 5px;"><strong>Particular/Service/<br>
                Agency Name</strong></th>
                <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;border-right: 1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px;border-right: 1px solid #000;">QTY</th>
                <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;border-right: 1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px;"><strong>Period</strong></th>
                <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;font-size: 16px;font-weight: bold; padding:10px 5px; border-right: 1px solid #000;"><strong>Amount</strong><br>
                  <strong>sanctioned for</strong>
                  <strong>payment (Rs)</strong></th>
                  <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;font-size: 16px;font-weight: bold; padding:10px 5px;border-right: 1px solid #000;"><strong>SLA Penalty</strong>
                    <strong>Amount /Liquidated damages (Rs.)</strong></th>
                    <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;font-size: 16px;font-weight: bold; padding:10px 5px;border-right: 1px solid #000;"><strong>Taxes as</strong>
                      <strong>applicable</strong></th>
                      <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;font-size: 16px;font-weight: bold; padding:10px 5px;border-right: 1px solid #000;"><strong>Withheld amount</strong></th>
                      <th  align="left" valign="top" style="font-family: arial;color: #000000;border-bottom:1px solid #000;font-size: 16px;font-weight: bold; padding:10px 5px;"><strong>Net payable</strong>
                        <strong>amount (Rs.)</strong></th>
                      </tr>
                     <!--  <tr border=1>
                        <th>Sr No</th>
                        <th>Invoice No.</th>
                        <th>Particular/Service/<br>
                        Agency Name</th>
                        <th>QTY</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>SLA Penalty</th>
                        <th><strong>Taxes as</strong>
                          <strong>applicable</strong></th>
                          <th>Withheld amount</th>
                          <th>Net payable</th>
                        </tr> -->

                        <?php $count = 1; 
                        ?>
                        
                        @foreach($data['result']['invoice_details'] as $item)
                        <?php ?>
                        <tr border=1 >
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $loop->iteration or $item->id }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['invoice_no'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['agency_name'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['qty'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['period'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['amount_payment'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['sla_amount'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['applicable_taxes'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['withheld_amount'] }}</td>
                          <td  valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['net_payable_amount'] }}</td>

                        </tr>
                        @endforeach
                        
                        <tr>
                          <td colspan="9" valign="middle" align="right" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000; "><strong>Total</strong></td>
                          <td valign="middle" align="center"><strong>{{isset($data['result']['total_payable_amount']['total_payable_amount']) ? $data['result']['total_payable_amount']['total_payable_amount'] : ''}}</strong></td>       
                          </tr> 
                      </tbody>
                    </table>
        </tr>
             @endif     
                  <tr>
                    <td colspan="3" align="left" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 20px 0px 20px 0px; ">The expenditure is  debitable to the following Head of Account for the financial year {{isset($data['result']['finacial_year']) ? $data['result']['finacial_year'] : ''}}</td>
                  </tr>
                  <tr>
                    <td  align="center" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">Object Head</td>
                    <td  valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">: </td>
                    <!-- <td  valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;"><strong></strong></td> -->
                  </tr>
                  <tr>
                    <td align="center" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">Budget Head</td>
                    <td valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;" >: {{isset($data['result']['budget_code']) ?$data['result']['budget_code'] : ''}} ({{isset($data['result']['broad_description']) ?$data['result']['broad_description'] : ''}})</td>
                    <!-- <td valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;"><strong></strong></td> -->
                  </tr>
                  <tr>
                    <td align="center" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">Code Head</td>
                    <td valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">: {{isset($data['result']['budget_head_of_acc']) ?$data['result']['budget_head_of_acc'] : ''}}</td>
                   <!--  <td valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;"><strong></strong></td> -->
                  </tr>
                  <tr><td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:20px; padding-bottom:10px;">This issues with the  approval of the DDG (Logistics) vide Diary No.<strong>_______________ dated _____________.</strong></td></tr>
                  <tr>
                    <td colspan="3" align="right" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:10px;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-bottom: 20px; "><table  border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td >&nbsp;</td>
                          <td  style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal;"><strong>({{isset($data['result']['dd_name']['users_name']) ?$data['result']['dd_name']['users_name'] : ''}})</strong></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:10px;">Deputy Director ({{isset($data['result']['department_name']) ?$data['result']['department_name'] : ''}})</td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; "><table border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                       @if(isset($data['result']['copy_to_details']) && !empty($data['result']['copy_to_details']))    
                       <tr>
                        <td align="left" valign="top" style="font-family: arial;color: #000000; font-size: 16px; font-weight: normal; padding: 10px 0px; text-decoration: none;">Copy to: </td>
                      </tr>
                       <?php $count=1;?>
                          @foreach($data['result']['copy_to_details'] as $copy)
                      <tr>
                         <td valign="center" align="left"  style="font-family: arial;color: #000000; font-size: 16px;font-weight: normal;padding-top:10px; "><?php echo $count++; ?>.
                          {{isset($copy['user_name'])? $copy['user_name'] : ''}} ({{isset($copy['designation'])? $copy['designation'] : ''}}) ,
                          {{isset($copy['department_name'])? $copy['department_name'] : ''}} , {{isset($copy['location_name'])? $copy['location_name'] : ''}}
                          
                        </td>
                      </tr>
                      @endforeach
                      <tr>
                        @endif    

                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table>
            </body>
            </html>

      <?php //exit;?>