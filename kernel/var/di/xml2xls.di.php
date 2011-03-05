<?php
/**
*	Интерфейс данных "xml2xls"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_xml2xls extends data_interface
{
	public $title = 'xml2xls';
	
        public function __construct()
	{
		// Call Base Constructor
		parent::__construct(__CLASS__);
        }

	public function generate($xml_file)
	{
		if (!file_exists($xml_file))
			throw new Exception("XML-file '{$xml_file}' NOT exosts.");

		//include LIB_PATH . 'PHPExcel/Writer/Excel2007.php';
		$xml = new SimpleXMLElement(file_get_contents($xml_file));
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator((string) $xml->body['creator']);
		$objPHPExcel->getProperties()->setLastModifiedBy((string) $xml->body['creator']);
		$objPHPExcel->getProperties()->setTitle((string) $xml->body['title']);
		$objPHPExcel->getProperties()->setSubject((string) $xml->body['subject']);
		$objPHPExcel->getProperties()->setDescription((string) $xml->body['description']);

		$n = 0;
		foreach ($xml->body->table as $table)
		{
			if ($n > 0) $objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($n++);
			$objPHPExcel->getActiveSheet()->setTitle((string) $table['title']);

			$row = 0;
			foreach ($table->tr as $tr)
			{
				$row++;
				$col = 0;
				foreach ($tr->td as $td)
				{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, (string) $td);
				}
			}
		}
		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . ((string) $xml->body['title']) . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}
?>
