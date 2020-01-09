<?php
  /**
   * Roles and Privileges
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: roles.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  if(!Users::checkAcl("owner")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::getAction()): case "privileges": ?>
<?php if(!$role = $db->first(Users::rTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $user->getPrivileges();?>
<?php $result = Utility::groupToLoop($data, "type");?>
<div class="wojo secondary icon message"> <i class="shield icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->M_TITLE5;?></div>
    <p><?php echo str_replace("[ROLE]", '<b>' . $role->name . '</b>', Lang::$word->M_INFO5);?> <?php echo ($role->code != "owner") ? '<b><i>' . Lang::$word->M_INFO5_1 . '</i></b>' : null;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <table class="wojo six column grid table">
    <thead>
      <tr>
        <th><?php echo Lang::$word->TYPE;?></th>
        <th><?php echo Lang::$word->ADD;?></th>
        <th><?php echo Lang::$word->EDIT;?></th>
        <th><?php echo Lang::$word->APPROVE;?></th>
        <th><?php echo Lang::$word->MANAGE;?></th>
        <th><?php echo Lang::$word->DELETE;?></th>
      </tr>
    </thead>
    <tbody>
      <?php
    foreach ($result as $type => $rows):
        echo '<tr>';
        echo '<td>' . $type . '</td>';
        echo '<td>';
        foreach ($rows as $i => $row):
            if (isset($row->mode) and $row->mode == "add") {
                $checked = ($row->active == 1) ? ' checked="checked"' : null;
                $is_owner = ($role->code == "owner") ? ' disabled="disabled"' : null;
                echo '<input class="wojo switch" data-toggler="true" type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" name="view-' . $row->id . '" ' . $is_owner . $checked . ' data-checkbox-options=\'{"toggle":true, "labels" : {"on": "' . Lang::$word->YES . '","off": "' . Lang::$word->NO . '"}, "customClass":"small"}\'><i class="icon pin" data-content="' . $row->pdesc . '"></i>';
            }
        endforeach;
        echo '</td>';
  
        echo '<td>';
        foreach ($rows as $row):
            if (isset($row->mode) and $row->mode == "edit") {
                $checked = ($row->active == 1) ? ' checked="checked"' : null;
                $is_owner = ($role->code == "owner") ? ' disabled="disabled"' : null;
                echo '<input class="wojo switch" data-toggler="true" type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" name="view-' . $row->id . '" ' . $is_owner . $checked . ' data-checkbox-options=\'{"toggle":true, "labels" : {"on": "' . Lang::$word->YES . '","off": "' . Lang::$word->NO . '"}, "customClass":"small"}\'><i class="icon pin" data-content="' . $row->pdesc . '"></i>';
            }
        endforeach;
        echo '</td>';
  
        echo '<td>';
        foreach ($rows as $row):
            if (isset($row->mode) and $row->mode == "approve") {
                $checked = ($row->active == 1) ? ' checked="checked"' : null;
                $is_owner = ($role->code == "owner") ? ' disabled="disabled"' : null;
                echo '<input class="wojo switch" data-toggler="true" type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" name="view-' . $row->id . '" ' . $is_owner . $checked . ' data-checkbox-options=\'{"toggle":true, "labels" : {"on": "' . Lang::$word->YES . '","off": "' . Lang::$word->NO . '"}, "customClass":"small"}\'><i class="icon pin" data-content="' . $row->pdesc . '"></i>';
            }
        endforeach;
        echo '</td>';

        echo '<td>';
        foreach ($rows as $row):
            if (isset($row->mode) and $row->mode == "manage") {
                $checked = ($row->active == 1) ? ' checked="checked"' : null;
                $is_owner = ($role->code == "owner") ? ' disabled="disabled"' : null;
                echo '<input class="wojo switch" data-toggler="true" type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" name="view-' . $row->id . '" ' . $is_owner . $checked . ' data-checkbox-options=\'{"toggle":true, "labels" : {"on": "' . Lang::$word->YES . '","off": "' . Lang::$word->NO . '"}, "customClass":"small"}\'><i class="icon pin" data-content="' . $row->pdesc . '"></i>';
            }
        endforeach;
        echo '</td>';
		
        echo '<td>';
        foreach ($rows as $row):
            if (isset($row->mode) and $row->mode == "delete") {
                $checked = ($row->active == 1) ? ' checked="checked"' : null;
                $is_owner = ($role->code == "owner") ? ' disabled="disabled"' : null;
                echo '<input class="wojo switch" data-toggler="true" type="checkbox" data-val="' . $row->active . '" value="' . $row->id . '" name="view-' . $row->id . '" ' . $is_owner . $checked . ' data-checkbox-options=\'{"toggle":true, "labels" : {"on": "' . Lang::$word->YES . '","off": "' . Lang::$word->NO . '"}, "customClass":"small"}\'><i class="icon pin" data-content="' . $row->pdesc . '"></i>';
            }
        endforeach;
        echo '</td>';
  
        echo '</tr>';
    endforeach;
  ?>
    </tbody>
  </table>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    $('body').on('change', '.wojo.switch', function() {
        status = $(this).is(':checked') ? 1 : 0;
        id = $(this).val();
        $.get(ADMINURL + "/controller.php", {
            updateRoleStatus: 1,
            id: id,
            active: status
        });
    });
});
// ]]>
</script>
<?php break;?>
<?php default: ?>
<?php $data = $user->getRoles();?>
<div class="wojo secondary icon message"> <i class="shield icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->M_TITLE4;?></div>
    <p><?php echo Lang::$word->M_INFO4;?></p>
  </div>
</div>
<div class="wojo basic segment">
  <table class="wojo table">
    <thead>
      <tr>
        <th></th>
        <th><?php echo Lang::$word->NAME;?></th>
        <th><?php echo Lang::$word->DESCRIPTION;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $row):?>
      <tr>
        <td><i class="<?php echo $row->icon;?> icon rounded"></i></td>
        <td><?php echo $row->name;?></td>
        <td><?php echo Validator::truncate($row->description,80);?></td>
        <td><a data-content="<?php echo Lang::$word->M_TITLE5;?>" href="<?php echo Url::adminUrl("roles", "privileges", false,"?id=" . $row->id);?>"><i class="rounded outline negative icon lock link"></i></a> <a data-roledesc="<?php echo $row->id;?>" data-name="<?php echo $row->name;?>"><i class="rounded outline positive icon pencil link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
    </tbody>
  </table>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
	$("[data-roledesc]").on('click', function () {
		id = $(this).data('roledesc');
		Messi.load(ADMINURL + '/helper.php', {
			loadRoleDescription: 1,
			id: id
		}, '',{
			title: "<?php echo Lang::$word->M_ROLEDESC_EDIT;?> / " + $(this).data('name'),
            buttons: [{
                id: 0,
				class: 'positive dosubmit',
                label: "<?php echo Lang::$word->SUBMIT;?>",
                val: 'action'
            }],
			callback: function (val) {}
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>