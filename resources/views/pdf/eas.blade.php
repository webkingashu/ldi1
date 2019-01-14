<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        @media print {
            .page-break {
                page-break-after: always;
            }
        }
        .td {
            font-family: arial;color: #000000;border-bottom:1px solid #000; font-size: 16px;font-weight: normal; padding:10px 5px; border-right: 1px solid #000;
        }
    </style>
</head>

<body>
    <table width="100%" align="center" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td align="center" valign="top" style="font-family: arial; font-size: 16px; font-weight: normal; padding: 20px; text-decoration: none;">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td align="center" valign="top" style="font-family: arial;color: #000000; font-size: 32px; font-weight: bold; padding: 10px 0px; text-decoration: none;">File No: {{isset($data['result']['file_number']) ? $data['result']['file_number'] : ''}}</td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="font-family: arial;color: #000000; font-size: 22px; padding: 10px 0px; text-decoration: none;">Unique Identification Authority of India</td>
                            </tr>
                             <tr>
                                <td align="center" valign="top" style="font-family: arial;color: #000000; font-size: 22px; padding: 10px 0px; text-decoration: none;">{{isset($data['result']['department_name']) ? $data['result']['department_name'] : ''}} Division</td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="font-family: arial;color: #000000; font-size: 22px; padding: 10px 0px; text-decoration: none;">UIDAI {{isset($data['result']['location_name']) ? $data['result']['location_name'] : ''}}</td>
                            </tr>
                           
                           
                            <tr>
                                <td align="center" valign="top" style="font-family: arial; font-size: 16px; font-weight: normal; padding: 20px; text-decoration: none;">
                                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top" style="font-family: arial;color: #1520a9; font-size: 32px; font-weight: bold; padding: 10px 0px; text-decoration: none;">Expenditure Angle Sanction (EAS)</td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top" style="font-family: arial;color: #000000; font-size: 20px; padding: 10px 0px; text-decoration: none; font-weight: bold;">Title of Sanction: {{isset($data['result']['sanction_title']) ? $data['result']['sanction_title'] : ''}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px;">
                                    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000000;">
                                        <tbody>
                                            <tr>
                                                <td  valign="center" align="left" class="td">1.</td>
                                                <td valign="center" align="left" class="td">Broad purpose of sanction: {{isset($data['result']['sanction_purpose']) ? $data['result']['sanction_purpose'] : ''}}</td>
                                                <td valign="center" align="left" class="td">{{isset($data['result']['sanction_title']) ? $data['result']['sanction_title'] : ''}}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">2.</td>
                                                <td valign="center" align="left" class="td">Govt. Authority or Schedule/Sub-Schedule/DoFP under which the sanction/order is being issued</td>
                                                <td valign="center" align="left" class="td">{{isset($data['result']['competent_authority']) ? ucwords(str_replace('_',' ',$data['result']['competent_authority'])) : ''}}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">3.</td>
                                                <td valign="center" align="left" class="td">Name of the item/items</td>
                                                <td valign="center" align="left" class="td">
                                                @if(isset($data['result']['item_details']) && !empty($data['result']['item_details']))    
                                                    <?php $count =1;?>   
                                                    @foreach($data['result']['item_details'] as $item)
                                                       {{$count++}}. {{$item['item']}} <br>
                                                    @endforeach
                                                @endif
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">4.</td>
                                                <td valign="center" align="left" class="td">Quantum of item/items being sanctioned</td>
                                                <td valign="center" align="left" class="td">@if(isset($data['result']['item_details']) && !empty($data['result']['item_details']))    
                                                    <?php $count =1;?>   
                                                    @foreach($data['result']['item_details'] as $item)
                                                       {{$count++}}. {{$item['item']}} <br>
                                                    @endforeach
                                                @endif</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">5.</td>
                                                <td valign="center" align="left" class="td">Value of sanction – Both per unit and total.</td>
                                                <td valign="center" align="left" class="td">Rs. {{isset($data['result']['sanction_total']) ? $data['result']['sanction_total'] : ''}}/- (Excluding taxes)<br><br>{{isset($data['result']['sanction_total_in_word']) ? $data['result']['sanction_total_in_word'] : ''}} (Rupees in words)</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">6.</td>
                                                <td valign="center" align="left" class="td">Major head, minor head, Sub Head and Detailed Head under which booking will be done.</td>
                                                <td valign="center" align="left" class="td">{{isset($data['result']['budget_head_of_acc']) ? $data['result']['budget_head_of_acc'] : '-'}}&nbsp;{{isset($data['result']['broad_description']) ? $data['result']['broad_description'] : '-'}}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">7.</td>
                                                <td valign="center" align="left" class="td">Validity of Sanction (period /dates): </td>
                                                <td valign="center" align="left" class="td">{{isset($data['result']['validity_sanction_period']) ? $data['result']['validity_sanction_period'] : 'N.A'}} </td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">8.</td>
                                                <td valign="center" align="left" class="td">Name of Payee Agency. </td>
                                                <td valign="center" align="left" class="td">{{isset($data['result']['vendor_name']) ? $data['result']['vendor_name'] : ''}} </td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">9.</td>
                                                <td valign="center" align="left" class="td">Approval of CFA given vide Note number_____ dated_____ in File number_______.</td>
                                                <td valign="center" align="left" class="td">Approval of CFA given vide Note number {{isset($data['result']['cfa_note_number']) ? $data['result']['cfa_note_number'] : ''}} dated {{isset($data['result']['cfa_dated']) ? $data['result']['cfa_dated'] : ''}} in File number {{isset($data['result']['cfa_note_number']) ? $data['result']['cfa_note_number'] : ''}}.</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">10.</td>
                                                <td valign="center" align="left" class="td">Whether being issued under inherent powers or with concurrence of FA.</td>
                                                <td valign="center" align="left" class="td">{{isset($data['result']['whether_being_issued_under']) && $data['result']['whether_being_issued_under'] == "fa_concurrence"  ? "Yes": "No"}}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">11.</td>
                                                <td valign="center" align="left" class="td">Financial Concurrence (FC) Number allotted by FA.</td>
                                                <td valign="center" align="left" class="td">FA Concurrence No. {{isset($data['result']['fa_number']) ? $data['result']['fa_number'] : ''}} dated {{isset($data['result']['fa_dated']) ? $data['result']['fa_dated'] : ''}} on page ____on F.No. ______________</td>
                                                
                                            </tr>
                                            <tr>
                                                <td  valign="center" align="left" class="td">12.</td>
                                                <td valign="center" align="left" class="td">Communication of sanction being signed by the undersigned under powers delegated by CFA to sign such financial documents vide CFA’s letter number _______ dated ________.</td>
                                                <td valign="center" align="left" class="td">As per Procurement Manual, 2014 Further, FA Concurrence No.{{isset($data['result']['fa_number']) ? $data['result']['fa_number'] : ''}} dated {{isset($data['result']['fa_dated']) ? $data['result']['fa_dated'] : ''}} on page 17/N on F.No. CFA Dy No. {{isset($data['result']['cfa_note_number']) ? $data['result']['cfa_note_number'] : ''}} dated {{isset($data['result']['cfa_dated']) ? $data['result']['cfa_dated'] : ''}}</td>
                                                
                                            </tr>
                                                                    
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                             <tr>
                                <td height="50"> &nbsp;&nbsp;&nbsp;</td>
                            </tr>

                            <tr>
                                <td>
                                    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:0;">
                                        <tbody>
                                            <tr>




                                                <td style="vertical-align: bottom;">
                                                    <table align="left" cellpadding="0" cellspacing="0">
                                                        <tbody>

                                                          <!--   <tr>
                                                                <td valign="top" style="font-family: arial;color: #000000; font-size: 16px; padding-top: 15px;"> &nbsp;&nbsp;&nbsp; &nbsp;</td>

                                                            </tr> -->
                                                            <tr>

                                                                <td valign="top" style="font-family: arial;color: #000000; font-size: 16px; padding-top: 15px;font-weight: bold;">Section Officer ({{isset($data['result']['slug']) ? $data['result']['slug'] : ''}})

                                                                </td>

                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </td>
                                                <td style="vertical-align: bottom; font-family: arial;color: #000000; font-size: 16px; padding-top: 20px;">
                                                    {{isset($data['result']['serial_no_of_sanction']) ? $data['result']['serial_no_of_sanction'] : ''}}
                                                </td>
                                                <td style="vertical-align: bottom; font-family: arial;color: #000000; font-size: 16px; padding-top: 15px;"> {{isset($data['result']['date_issue']) ? $data['result']['date_issue'] : ''}}</td>
                                                <td style="vertical-align: bottom; text-align:right; font-family: arial;color: #000000; font-size: 16px; padding-top: 15px;">{{isset($data['result']['file_number']) ? $data['result']['file_number'] : ''}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="30">&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                        @if(isset($data['result']['copy_to_details']) && !empty($data['result']['copy_to_details']))    
                            <tr>
                                <td>
                                    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:0;">
                                        <tr>
                                            <td style="padding-bottom: 15px; font-weight:bold;">Copy to</td>
                                        </tr>
                                        <?php $count=1;?>
                                        @foreach($data['result']['copy_to_details'] as $copy)
                                        <tr>
                                            <td style="padding-bottom: 10px;"><?php echo $count++; ?>.
                                              {{isset($copy['user_name'])? $copy['user_name'] : ''}} ({{isset($copy['designation'])? $copy['designation'] : ''}}) ,
                                              {{isset($copy['department_name'])? $copy['department_name'] : ''}}   
                                           </td>
                                        </tr>
                                        @endforeach
                                       
                                    </table>
                                </td>

                            </tr>
                        @endif    
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
<?php //exit; ?>
