<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
  <table width="100%" align="center" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td colspan="3"  align="center" valign="top" style="font-family: arial;color: #000000; font-size: 18px; padding-top: 10px; text-decoration: none; font-weight: bold;">Government of India<br>
          Ministry of Electronics & IT<br>
          Department of Electronics & Information Technology (Deity)
          <br>
        UNIQUE IDENTIFICATION AUTHORITY OF INDIA </td>
      </tr>
      <tr>
        <td colspan="3" align="center" valign="top" style="padding-top:15px; padding-bottom:15px;font-family: arial;color: #000000; font-size: 19px;font-weight: normal; border-bottom: #000000 solid 1px;">{{isset($data['result']['department_name']) ?$data['result']['department_name'] : ''}}</td>
      </tr>
      <tr>
        <td colspan="3" align="center" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong><u>Notification of  Award/Purchase Order</u></strong></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>File No.:</strong>{{isset($data['result']['file_number']) ?$data['result']['file_number'] : ''}}</td>
        <td width="275" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>Date: <?php echo date("d/m/Y");?></strong></td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>Vendor Name: </strong> <?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><strong>Address: </strong> <?php echo isset($data['result']['vendor_address']) ? $data['result']['vendor_address']: ''; ?></td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">Kindly Attn:</td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">Subject:<?php echo isset($data['result']['subject']) ? $data['result']['subject']: ''; ?></td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">Ref: Tender No. {{isset($data['result']['bid_number']) ?$data['result']['bid_number'] : ''}} dated {{isset($data['result']['date_of_bid']) ?$data['result']['date_of_bid'] : ''}}</td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">Sir,</td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please refer to the bid  document submitted by <strong><?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></strong> against above referred tender dated {{isset($data['result']['date_of_bid']) ?$data['result']['date_of_bid'] : ''}}  and post negotiation letter submitted by <strong><?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></strong> dated ­­­­­­_________.UIDAI  is placing the order for <strong><?php echo isset($data['result']['subject']) ? $data['result']['subject']: ''; ?></strong> for a period of _____ year with effect  from the date of activation of services.</td>
      </tr>
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal; padding-left:30px;">The details of the items  and costs are as here under:</td>
      </tr>
      @if(isset($data['result']['item_details']) && count($data['result']['item_details']) >0)
      <tr>
        <td colspan="3" align="left" valign="top" style="padding-top:20px;font-family: arial;color: #000000; font-size: 16px;font-weight: normal;"><table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000000;">
          <tbody>
            <tr>
              <th width="10%" align="center" valign="middle" style="font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px; border-right: 1px solid #000;">Sr No</th>
              <th width="15%" align="center" valign="middle" style="font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px; border-right: 1px solid #000;">Category</th>
              <th width="13%" align="center" valign="middle" style="font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: bold;border-right: 1px solid #000; padding:10px 5px;">ITEM</th>
              <th width="18%" align="center" valign="middle" style="font-family: arial;color: #000000;border-bottom:1px solid #000;border-right: 1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px;border-right: 1px solid #000;">QTY</th>
              <th width="23%" align="center" valign="middle" style="font-family: arial;color: #000000;border-bottom:1px solid #000;border-right: 1px solid #000; font-size: 16px;font-weight: bold; padding:10px 5px;">Unit Price Excl Tax</th>
              <th width="21%" align="center" valign="middle" style="font-family: arial;color: #000000;border-bottom:1px solid #000;font-size: 16px;font-weight: bold; padding:10px 5px;">Total Price Excl Tax</th>
            </tr>
          
                        
                          <?php $count = 1; 
                        ?>
                        @foreach($data['result']['item_details'] as $item)
                        <?php ?>
                        <tr border=1 >
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $count++ }}</td>
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word; ">{{ $item['category'] }}</td>
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word; ">{{ $item['item'] }}</td>
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['qty'] }}</td>
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['unit_price_tax'] }}</td>
                          <td valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 14px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000;word-break: break-word;">{{ $item['total_unit_price_tax'] }}</td>
                        </tr>
                        @endforeach
                      
                         <tr>
                          <td colspan="5" valign="middle" align="right" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;border-bottom: 1px solid #000; "><strong>Total</strong></td>
                          <td valign="middle" align="center"><strong>{{isset($data['result']['total_price_tax']['total_price_tax']) ?$data['result']['total_price_tax']['total_price_tax'] : ''}}</strong></td>       
                          </tr> 
      
         <!--    <tr>
              <th valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000; ">&nbsp;</th>
              <th valign="middle" align="center" style="font-family: arial;color: #000000; font-size: 16px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;  ">&nbsp;</th>
              <th valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal;border-right: 1px solid #000; padding:10px 5px; ">&nbsp;</th>
              <th valign="middle" align="center" style="font-family: arial;color: #000000;border-right: 1px solid #000; font-size: 16px;font-weight: normal; padding:10px 5px;border-right: 1px solid #000; ">&nbsp;</th>
              <th valign="middle" align="center" style="font-family: arial;color: #000000;border-right: 1px solid #000; font-size: 16px;font-weight: normal; padding:10px 5px; ">&nbsp;</th>
              <th valign="middle" align="center" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding:10px 5px; ">&nbsp;</th>
            </tr> -->
          </tbody>
        </table></td>
      </tr>
      @endif
      <tr><td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:20px; padding-bottom:10px;"><strong>Terms of Payment:</strong></td></tr>
      <tr>
        <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The payment will be released on quarterly basis to Vendor Name after completion of each quarter after the receipt of invoice and on furnishing the certificate duly recommended by the respective Data Centre in-charge on satisfactory completion of maintenance. The documents to be attached along with the invoice are annexed as Annexure 1, Annexure 2 &amp; Annexure 3 on quarterly basis. All the documents should be duly certified by ADG <strong>(Operations)</strong>.</td>
      </tr>
      <tr>
        <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:20px; padding-bottom:10px;"><strong>Performance Guarantee:</strong></td></tr>
        <tr>
          <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></strong>will submit performance bank guarantee equivalent  to 10% of the value of AMC i.e Rs_________ valid for ­__________.</td>
        </tr>
        <tr>
          <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 20px 0px 5px 0px;"><strong>Liquidated Damages:</strong></td>
        </tr>
        <tr>
          <td width="36" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">i.</td>
          <td colspan="2" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">Liquidated damages will  be applied if the <u>vendor is not able to start the AMC services within two(2)  weeks of the award of contract, </u>the Purchaser shall without prejudice to  its other remedies under the Contract, deduct from the liquidated damages, a  sum equivalent to the 0.5 percent per day or part thereof from the bill of  first quarter, upto maximum deduction of 5% of the bill of first quarter. If  delayed for more than five weeks, the Purchaser may consider termination of the  Contract.</td>
        </tr>
        <tr>
          <td width="36" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">ii.</td>
          <td colspan="2" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">SLA related Liquidated Damage will be charged @0.5% for Non-Performance of the services not provided on the items  for the period invoices are to be claimed and further as mentioned in Clause 32 of Section III of RFP. The maximum amount of LD will be levied@10% of the value of order.</td>
        </tr>
        <tr><td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:20px; padding-bottom:10px;"><strong>Currency of Payment:</strong></td></tr>
        <tr>
          <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment will be made in  Indian Rupees only.</td>
        </tr>
        <tr><td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:20px; padding-bottom:10px;"><strong>Taxes:</strong></td></tr>
        <tr>
          <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Taxes will be paid on  actual basis on submission  of  documentary evidences by <strong><?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?></strong></td>
        </tr>
        <tr>
         <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:20px; padding-bottom:10px;"><strong>Services to be provided:</strong></td></tr>
         <tr>
          <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;As mentioned in the  Section-V Scope of work of the RFP.</td>
        </tr>
        <tr>
          <td height="50" colspan="3" align="left" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;">The other terms and  conditions will be as per the bid document .</td>
        </tr>
        <tr>
          <td colspan="3" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding: 5px 0px 5px 0px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo isset($data['result']['vendor_name']) ? $data['result']['vendor_name']: ''; ?> is</strong> requested to accept the terms and conditions as  stated above within three days of the receipt   of this letter and also sign the contract document within 15 days from  the receipt  of this letter.</td>
        </tr>
        <tr>
          <td colspan="3" align="right" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="right" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal;"><strong>Yours Sincerely,</strong></td>
        </tr>
        <tr>
          <td colspan="3" align="right" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; padding-top:10px;"><strong>Deputy Director ({{isset($data['result']['dept_slug']) ? $data['result']['dept_slug'] : ''}})</strong></td>
        </tr>

        <tr>
          <td colspan="3" align="left" valign="top" style="font-family: arial;color: #000000;font-size: 16px;font-weight: normal; "><table width="100%" border="0" cellpadding="0" cellspacing="0">
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
             <!--  <tr> -->
               @endif 


            </tbody>
          </table></td>
        </tr>

      </tbody>
    </table>
  </body>
  </html>
  <?php //exit;?>