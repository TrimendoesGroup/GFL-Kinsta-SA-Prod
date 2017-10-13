<?php

class FMControllerSubmissions_fm {
	public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html($_POST['task']) : '');
    $id = ((isset($_POST['current_id'])) ? (int)esc_html($_POST['current_id']) : 0);
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);

    if (method_exists($this, $task)) {
      if ($task != 'show_stats') {
        check_admin_referer('nonce_fm', 'nonce_fm');
      }
      else {
        check_ajax_referer('nonce_fm_ajax', 'nonce_fm_ajax');
      }
      $this->$task($id);
    }
    else {
      $this->display($form_id);
    }
  }
  
	public function display($form_id) {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/admin/models/FMModelSubmissions_fm.php";
    $model = new FMModelSubmissions_fm();

    require_once WD_FM_DIR . "/admin/views/FMViewSubmissions_fm.php";
    $view = new FMViewSubmissions_fm($model);
    $view->display($form_id);
  }

	public function show_stats() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/admin/models/FMModelSubmissions_fm.php";
    $model = new FMModelSubmissions_fm();

    require_once WD_FM_DIR . "/admin/views/FMViewSubmissions_fm.php";
    $view = new FMViewSubmissions_fm($model);
    $view->show_stats($form_id);
  }

	public function edit() {
    global $wpdb;
    require_once WD_FM_DIR . "/admin/models/FMModelSubmissions_fm.php";
    $model = new FMModelSubmissions_fm();

    require_once WD_FM_DIR . "/admin/views/FMViewSubmissions_fm.php";
    $view = new FMViewSubmissions_fm($model);
    $id = ((isset($_POST['current_id']) && esc_html($_POST['current_id']) != '') ? (int)$_POST['current_id'] : 0);

    $form_id = (int)$wpdb->get_var("SELECT form_id FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id='" . $id . "'");
    $form = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id='" . $form_id . "'");

    $view->edit($id);
  }
  
  public function save() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    $this->save_db();
    $this->display($form_id);
  }

  public function apply() {
    $this->save_db();
    $this->edit();
  }
  
  public function save_db() {
    global $wpdb;
    $id = (isset($_POST['current_id']) ? (int)esc_html(stripslashes($_POST['current_id'])) : 0);
    $date = esc_html($_POST['date']);
    $ip = esc_html($_POST['ip']);
    $form_id = (int)$wpdb->get_var("SELECT form_id FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id='" . $id . "'");
    $form = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id='" . $form_id . "'");
    $label_id = array();
    $label_order_original = array();
    $label_type = array();

    if (strpos($form->label_order, 'type_paypal_')) {
      $form->label_order = $form->label_order . "0#**id**#Payment Status#**label**#type_paypal_payment_status#****#";
    }
    $label_all = explode('#****#', $form->label_order);
    $label_all = array_slice($label_all, 0, count($label_all) - 1);
    foreach ($label_all as $key => $label_each) {
      $label_id_each = explode('#**id**#', $label_each);
      array_push($label_id, $label_id_each[0]);
      $label_oder_each = explode('#**label**#', $label_id_each[1]);
      array_push($label_order_original, $label_oder_each[0]);
      array_push($label_type, $label_oder_each[1]);
    }

    foreach ($label_id as $key => $label_id_1) {
      if (isset($_POST["submission_" . $label_id_1])) {
        $element_value = (isset($_POST["submission_" . $label_id_1]) ? esc_html(stripslashes($_POST["submission_" . $label_id_1])) : " ");
        $query = "SELECT id FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id='" . $id . "' AND element_label='" . $label_id_1 . "'";
        $result = $wpdb->get_var($query);
        if ($label_type[$key] == 'type_file_upload') {
          if ($element_value) {
            $element_value = $element_value . "*@@url@@*";
          }
        }
        if ($result) {
          $save = $wpdb->update($wpdb->prefix . "formmaker_submits", array(
            'element_value' => stripslashes($element_value),
          ), array(
            'group_id' => $id,
            'element_label' => $label_id_1
          ), array(
            '%s',
          ), array(
            '%d',
            '%s'
          ));
        }
        else {
          $save = $wpdb->insert($wpdb->prefix . "formmaker_submits", array(
            'form_id' => $form_id,
            'element_label' => $label_id_1,
            'element_value' => stripslashes($element_value),
            'group_id' => $id,
            'date' => $date,
            'ip' => $ip
          ), array(
              '%d',
              '%s',
              '%s',
              '%d',
              '%s',
              '%s'
            )
          );
        }
      }
      else {
        if (isset($_POST["submission_" . $label_id_1 . '_0'])) {
          $element_value = '';
          for ($z = 0; $z < 21; $z++) {
            $element_value_ch = isset($_POST["submission_" . $label_id_1 . '_' . $z]) ? $_POST["submission_" . $label_id_1 . '_' . $z] : null;
            if (isset($element_value_ch)) {
              $element_value = $element_value . $element_value_ch . '***br***';
            }
            else {
              break;
            }
          }
          $query = "SELECT id FROM " . $wpdb->prefix . "formmaker_submits WHERE group_id='" . $id . "' AND element_label='" . $label_id_1 . "'";
          $result = $wpdb->get_var($query);
          if ($result) {
            $save = $wpdb->update($wpdb->prefix . "formmaker_submits", array(
              'element_value' => stripslashes($element_value),
            ), array(
              'group_id' => $id,
              'element_label' => $label_id_1
            ), array(
              '%s',
            ), array(
              '%d',
              '%s'
            ));
          }
          else {
            $save = $wpdb->insert($wpdb->prefix . "formmaker_submits", array(
              'form_id' => $form_id,
              'element_label' => $label_id_1,
              'element_value' => stripslashes($element_value),
              'group_id' => $id,
              'date' => $date,
              'ip' => $ip
            ), array(
                '%d',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s'
              )
            );
          }
        }
      }
    }
    if ($save !== FALSE) {
      echo WDW_FM_Library::message('Submission Succesfully Saved.', 'updated');
    }
    else {
      echo WDW_FM_Library::message('Error. Please install plugin again.', 'error');
    }
  }
  
  public function delete($id) {
    global $wpdb;
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id="%d"', $id);
    if ($wpdb->query($query)) {
      echo WDW_FM_Library::message('Item Succesfully Deleted.', 'updated');
    }
    else {
      echo WDW_FM_Library::message('Error. Please install plugin again.', 'error');
    }

    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_sessions WHERE form_id="%d" AND group_id="%d"', $form_id, $id);
    $wpdb->query($query);

    $this->display($form_id);
  }
  
  public function delete_all() {
    global $wpdb;
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? esc_html($_POST['form_id']) : 0);
    $cid = ((isset($_POST['post']) && $_POST['post'] != '') ? $_POST['post'] : NULL);
    if (count($cid)) {
      array_walk($cid, create_function('&$value', '$value = (int)$value;'));
      $cids = implode(',', $cid);
      $query = 'DELETE FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id IN ( ' . $cids . ' )';

      if ($wpdb->query($query)) {
        echo WDW_FM_Library::message('Items Succesfully Deleted.', 'updated');
      }
      else {
        echo WDW_FM_Library::message('Error. Please install plugin again.', 'error');
      }

      $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_sessions WHERE form_id="%d" AND group_id IN ( "%s" )', $form_id, $cids);
      $wpdb->query($query);
    }
    else {
      echo WDW_FM_Library::message('You must select at least one item.', 'error');
    }
    $this->display($form_id);
  }

  public function block_ip() {
    global $wpdb;
    $flag = FALSE;
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    $cid = ((isset($_POST['post']) && $_POST['post'] != '') ? $_POST['post'] : NULL);
    if (count($cid)) {
      array_walk($cid, create_function('&$value', '$value = (int)$value;'));
      $cids = implode(',', $cid);
      $query = 'SELECT * FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id IN ( ' . $cids . ' )';
      $rows = $wpdb->get_results($query);
      foreach ($rows as $row) {
        $ips = $wpdb->get_var($wpdb->prepare('SELECT ip FROM ' . $wpdb->prefix . 'formmaker_blocked WHERE ip="%s"', $row->ip));
        $flag = TRUE;
        if (!$ips) {
          $save = $wpdb->insert($wpdb->prefix . 'formmaker_blocked', array(
            'ip' => $row->ip,
          ), array(
            '%s',
          ));
        }
      }
    }
    if ($flag) {
      echo WDW_FM_Library::message('IPs Succesfully Blocked.', 'updated');
    }
    else {
      echo WDW_FM_Library::message('You must select at least one item.', 'error');
    }
    $this->display($form_id);
  }

  public function unblock_ip() {
    global $wpdb;
    $flag = FALSE;
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    $cid = ((isset($_POST['post']) && $_POST['post'] != '') ? $_POST['post'] : NULL);
    if (count($cid)) {
      array_walk($cid, create_function('&$value', '$value = (int)$value;'));
      $cids = implode(',', $cid);
      $query = 'SELECT * FROM ' . $wpdb->prefix . 'formmaker_submits WHERE group_id IN ( ' . $cids . ' )';
      $rows = $wpdb->get_results($query);
      foreach ($rows as $row) {
        $flag = TRUE;
        $ips = $wpdb->get_var($wpdb->prepare('SELECT ip FROM ' . $wpdb->prefix . 'formmaker_blocked WHERE ip="%s"', $row->ip));
        if ($ips) {
          $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'formmaker_blocked WHERE ip="%s"', $ips));
        }
      }
    }
    if ($flag) {
      echo WDW_FM_Library::message('IPs Succesfully Unblocked.', 'updated');
    }
    else {
      echo WDW_FM_Library::message('You must select at least one item.', 'error');
    }
    $this->display($form_id);
  }

}