<?php
/**
 * The newest version of the MySQL adapter includes a hack to type number column
 * types as numbers, despite the fact that PHP does not offer this kind of info by default
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright (c) 2003 amfphp.org
 * @package flashservices
 * @subpackage adapters
 * @version $Id: mysqlAdapter.php,v 1.1 2005/07/05 07:56:29 pmineault Exp $
 */

require_once(AMFPHP_BASE . "shared/adapters/RecordSetAdapter.php");

class mysqlAdapter extends RecordSetAdapter {
	/**
	 * Constructor method for the adapter.  This constructor implements the setting of the
	 * 3 required properties for the object.
	 * 
	 * @param resource $d The datasource resource
	 */
	 
	function mysqlAdapter($d) {
		
		parent::RecordSetAdapter($d);
		$fieldcount = mysqli_num_fields($d);
		$intFields = array();
		for($i = 0; $i < $fieldcount; $i++)
		{
			$tipocampo = mysqli_fetch_field_direct($d, $i);
                    //$tipocampo = mysqli_fetch_field($result,"1"); $tipocampo->type 
			$this->columns[] = $tipocampo->name;
			$type = $tipocampo->type;
			if(in_array($type, array('int', 'real', 'year')))
			{
				$intFields[] = $i;
			}
		}
		
		while($row = mysqli_fetch_row($d))
		{
			foreach($intFields as $key => $val)
			{
				$row[$val] = (float) $row[$val];
			}
			$this->rows[] = $row;
		}
	}
}

?>