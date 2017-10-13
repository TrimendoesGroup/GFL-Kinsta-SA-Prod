<?php

class FMViewFormmakerwindow {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    $rows = $this->model->get_form_data();
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>Form Maker</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php
          wp_print_scripts('jquery');
          wp_print_scripts('jquery-ui-tabs');
        ?>
        <link rel="stylesheet" href="<?php echo WD_FM_URL; ?>/css/jquery-ui-1.12.1.css">
        <style>
          #WD-FormMaker {
            margin-top: 0;
            outline:none;
          }         
          #WD-FormMaker #tabs {
            display:none;
            min-height: 335px;
          }
          #WD-FormMaker #tab-Submissions ul {
            list-style-type: none;
          }
          #WD-FormMaker .ui-widget-header {
            border-bottom: 1px solid #dddddd;
            background: transparent;
            font-size: 12px;
          }
          #WD-FormMaker .ui-tabs .ui-tabs-panel {
            font-size: 12px;
            border: 1px solid #c5c5c5;
            border-top:none;
            margin-left: 3px;
            margin-right: 2px;
            height: 280px;
          }
          #WD-FormMaker .ui-widget-header {
            border-style: none;
            border-bottom: 1px solid #dddddd;
            background: transparent;
          }

          #WD-FormMaker .ui-widget.ui-widget-content {
            border-style: none;
          }

          #WD-FormMaker .ui-state-active,
          #WD-FormMaker .ui-widget-content
          #WD-FormMaker .ui-state-active,
          #WD-FormMaker .ui-widget-header
          #WD-FormMaker .ui-state-active{
            border: 1px solid #c5c5c5;
            border-bottom: none;
            background: #f6f6f6;
            outline:none
          }

          #WD-FormMaker .mceActionPanel #insert {
            float: right;
            outline:none;
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            line-height: 26px;
            height: 28px;
            margin: 5px 5px 0 0;
            padding: 0 10px 1px;
            cursor: pointer;
            border-width: 1px;
            border-style: solid;
            -webkit-appearance: none;
            -webkit-border-radius: 3px;
            -webkit-box-sizing: border-box;
            -webkit-box-shadow: 0 1px 0 #ccc;
            box-shadow: 0 1px 0 #ccc;
            border-radius: 3px;
            white-space: nowrap;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            background: #0085ba;
            border-color: #0073aa #006799 #006799;
            -webkit-box-shadow: 0 1px 0 #006799;
            box-shadow: 0 1px 0 #006799;
            color: #fff;
            text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;
          }
          #WD-FormMaker .ui-state-active a,
          #WD-FormMaker .ui-state-active a:link,
          #WD-FormMaker .ui-state-active a:visited {
            background: #ffffff;
            color: #000000;
            outline:none
          }
          #WD-FormMaker table {
            font-size:12px;
          }
        </style>
        <base target="_self">
      </head>
      <body id="WD-FormMaker">
        <div id="tabs">
            <ul>
                <li><a href="#tab-FormMaker">Form Maker</a></li>
                <li><a href="#tab-Submissions">Submissions</a></li>
            </ul>
            <div id="tab-FormMaker" class="panel">
                <table>
                    <tr>
                        <td style="vertical-align: middle; text-align: left;">Select a Form</td>
                        <td style="vertical-align: middle; text-align: left;">
                            <select name="form_maker_id" id="form_maker_id" style="width: 230px; text-align: left;">
                                <option value="0" selected="selected">- Select a Form -</option>
                                <?php
                                foreach ($rows as $row) {
                                    ?>
                                    <option value="<?php echo $row->id; ?>"><?php echo $row->title; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-Submissions">
                    <div class="error" style="position: relative; padding:0 5px 10px 5px; font-size: 15px; color: red; opacity: 1; font-weight: bolder;">Front end submissions are disabled in free version.</div>
                    <div style="position: absolute; background: gray; width: 93%; height: 75%; opacity: 0.3;"></div>
                    <table>
                        <tr>
                            <td class="smaller_font">Select a Form:</td>
                            <td class="smaller_font">
                                <select name="submissions_id" id="submissions_id" style="width: 230px; text-align: left;">
                                    <option value="- Select Form -" selected="selected">- Select a Form -</option>
                                    <?php
                                    foreach ($rows as $row) {
                                        ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->title; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="smaller_font">Select Date Range:</td>
                            <td class="smaller_font">
                                <!--<label style="min-width:30px !important;">From:</label>-->
                                <input class="inputbox" type="text" name="startdate" id="startdate" size="10" maxlength="10" value="" />
                                <input type="reset" style="width: 22px;" class="button" value="..." onclick="return showCalendar('startdate','%Y-%m-%d');" />
                                <label style="min-width:30px !important;">To:</label>
                                <input class="inputbox" type="text" name="enddate" id="enddate" size="10" maxlength="10" value="" />
                                <input type="reset" style="width: 22px;" class="button" value="..." onclick="return showCalendar('enddate','%Y-%m-%d');" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%">
                                    <tr>
                                        <td style="border-right: 1px solid black;">
                                            <table>
                                                <tr>
                                                    <td class="smaller_font" style="vertical-align: top;">Select fields:</td>
                                                    <td class="smaller_font">
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="submit_date" name="submit_date" value="submit_date">
                                                                <label for="submit_date">Submit Date</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="submitter_ip" name="submitter_ip" value="submitter_ip">
                                                                <label for="submitter_ip">Submitter's IP Address</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="username" name="username" value="username">
                                                                <label for="username">Submitter's Username</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="useremail" name="useremail" value="useremail">
                                                                <label for="useremail">Submitter's Email Address</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="form_fields" name="form_fields" value="form_fields">
                                                                <label for="form_fields">Form Fields</label>
                                                            </li>
                                                            <li><label style="font-size: 10px; width: 160px;">You can hide specific form fields from Form General Options.</label></li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="smaller_font" style="vertical-align: top;">Export to:</td>
                                                    <td class="smaller_font">
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="csv" name="csv" value="csv">
                                                                <label for="csv">CSV</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="xml" name="xml" value="xml">
                                                                <label for="xml">XML</label>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <table>
                                                <tr>
                                                    <td class="smaller_font" style="vertical-align: top;">Show:</td>
                                                    <td class="smaller_font" style="width:240px;">
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="title" name="title" value="title">
                                                                <label for="title">Title</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="search" name="search" value="search">
                                                                <label for="search">Search</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="ordering" name="ordering" value="ordering">
                                                                <label for="ordering">Ordering</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="entries" name="entries" value="entries">
                                                                <label for="entries">Entries</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="views" name="views" value="views">
                                                                <label for="views">Views</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="conversion_rate" name="conversion_rate" value="conversion_rate">
                                                                <label for="conversion_rate">Conversion Rate</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="pagination" name="pagination" value="pagination">
                                                                <label for="pagination">Pagination</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" checked="checked" id="stats" name="stats" value="stats">
                                                                <label for="stats">Statistics</label>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
            </div>
        </div>
        <div class="mceActionPanel">
            <input type="submit" id="insert" name="insert" value="Insert" onClick="form_maker_insert_shortcode();"/>
        </div>
        <script type="text/javascript">
            jQuery.noConflict();
            jQuery(document).ready(function() {                     
              jQuery("#tabs").tabs(); 
              jQuery('#WD-FormMaker #tabs').show();
            });

            var short_code = get_params("Form");
            if (short_code) {
                if (!short_code['type']) {
                  document.getElementById("form_maker_id").value = short_code['id'];
                }
            }
          // Get shortcodes attributes.
          function get_params(module_name) {
            var selected_text = top.tinyMCE.activeEditor.selection.getContent();
            var module_start_index = selected_text.indexOf("[" + module_name);
            var module_end_index = selected_text.indexOf("]", module_start_index);
            var module_str = "";
            if ((module_start_index == 0) && (module_end_index > 0)) {
              module_str = selected_text.substring(module_start_index + 1, module_end_index);
            }
            else {
              return false;
            }
            var params_str = module_str.substring(module_str.indexOf(" ") + 1);
            var key_values = params_str.split(" ");
            var short_code_attr = new Array();
            for (var key in key_values) {
              var short_code_index = key_values[key].split('=')[0];
              var short_code_value = key_values[key].split('=')[1];
              short_code_value = short_code_value.substring(1, short_code_value.length - 1);
              short_code_attr[short_code_index] = short_code_value;
            }
            return short_code_attr;
          }

          function form_maker_insert_shortcode() {
            if (jQuery('#tabs .ui-tabs-panel[aria-hidden=false]').attr('id') == 'tab-FormMaker') {
              if (document.getElementById('form_maker_id').value == '0') {
                  top.tinyMCE.activeEditor.windowManager.close(window);
              }
              else {
                var tagtext;
                tagtext = '[Form id="' + document.getElementById('form_maker_id').value + '"]';
                    top.tinyMCE.execCommand('mceInsertContent', false, tagtext);
                    top.tinyMCE.activeEditor.windowManager.close(window);
              }
            }
            else {
              alert("Front end submissions are disabled in free version.");
            }
          }
        </script>
      </body>
    </html>
    <?php
    die();
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}