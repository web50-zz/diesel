<?php
/**
*	Библиотека для преобразования XML в Excel
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class xml2xls
{
        public function __construct()
	{
        }

	public function generate($xml_file,$type = 'FILE')
	{
		if($type == 'FILE')
		{
			if (!file_exists($xml_file))
				throw new Exception("XML-file '{$xml_file}' NOT exosts.");
		//include LIB_PATH . 'PHPExcel/Writer/Excel2007.php';
		$xml = new SimpleXMLElement(file_get_contents($xml_file));
		}
		elseif($type == 'TEXT') {
			$xml = new SimpleXMLElement($xml_file);
		}
		else
		{
				throw new Exception("Wrong XML declaration");

		}
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
					$styleArray = array();
					$current_cell = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,$row);
					//9* высота строки  выставляется по последне заданной высоте ячейки в строке
					if($td['height'] != '')
					{
						$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight($td['height']);
					}
					//9* <td border="1"> //9* пока только бордер может быть равен 1, автоматом делает аутлайн и THIN  других бордеров пока не поддереживаем
					if($td['border'] >0)
					{
						$styleArray = array(
								'borders' => array(
								'outline' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
									'color' => array('rgb' => '000000'),
									),
								),
							);
					}
					//9* <td bgcolor="ffffff"> Внимание -   цвета задается просто  RGB без #  например cccccc или  b0fecf  
					if($td['bgcolor']!= '')
					{
						$styleArray['fill'] = array(
							'type'=>PHPExcel_Style_Fill::FILL_SOLID,
							'color'=>array('rgb'=>$td['bgcolor']),
						);
					
					}
					// 9* вертикальный  align
					if($td['valign'] != '')
					{
						if($td['valign'] == 'center')
						{
							$styleArray['alignment']['vertical']= PHPExcel_Style_Alignment::VERTICAL_CENTER;
						}
						if($td['valign'] == 'top')
						{
							$styleArray['alignment']['vertical']=PHPExcel_Style_Alignment::VERTICAL_TOP;
						}
						if($td['valign'] == 'bottom')
						{
							$styleArray['alignment']['vertical']=PHPExcel_Style_Alignment::VERTICAL_TOP;
						}
						if($td['valign'] == 'justify')
						{
							$styleArray['alignment']['vertical']=PHPExcel_Style_Alignment::VERTICAL_JUSTIFY;
						}
					}
					//9* горизонтальный Align
					if($td['align'] != '')
					{
						if($td['align'] == 'center')
						{
							$styleArray['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
						}
						if($td['align'] == 'left')
						{
							$styleArray['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
						}
						if($td['align'] == 'right')
						{
							$styleArray['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
						}
						if($td['align'] == 'justify')
						{
							$styleArray['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY;
						}
					}
					//9* <td bold="1">
					if($td['bold'] !='')
					{
						$styleArray['font']['bold'] = true;
					}
					//9* <td color="ffffff"> set font-color
					if($td['color'] !='')
					{
						$styleArray['font']['color'] = array('rgb'=>$td['color']);
					}
					//9* <td font-name="Arial">
					if($td['font-name'] != '')
					{
						$styleArray['font']['name'] = $td['font-name'];
					}
					else
					{
						$styleArray['font']['name'] = 'Arial';//9* Arial by default
					}
					//9* <td wrap="1"> wraps it
					if($td['wrap'] != '')
					{
						$styleArray['alignment']['wrap'] = true;
					}
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, (string) $td);
					if($td['merge']>0)
					{
						$merge_to_cell =  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col+$td['merge'],$row);
						$coord1 = $current_cell->getCoordinate();
						$coord2 = $merge_to_cell->getCoordinate();
						$objPHPExcel->getActiveSheet()->mergeCells("$coord1:$coord2");
						$start_merge_coulumn = $col;
						$col = $col+$td['merge'];
						//9* ниже надо чтобы бордер  райт у последней замерженой ячейки также выставился
						
						if($td['border']>0)
						{
							$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
							//9* msExcel patch 
							for($i=$start_merge_column+1;$i<$col;$i++)
							{
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							}
						}
					}
					$col++;
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
