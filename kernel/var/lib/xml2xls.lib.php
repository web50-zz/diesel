<?php
/**
*	Библиотека для преобразования XML в Excel
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class xml2xls
{
	/**
	* @access	protected
	* @var	array	$styles	Массив предопределённых стилей
	*/
	protected $styles = array();

	/**
	* @access	protected
	* @var	array	$merge	Матрица объединений ячеек
	*/
	protected $merge = array();

        public function __construct()
	{
        }

	public function generate($xml_file,$type = 'FILE')
	{
		if ($type == 'FILE')
		{
			if (!file_exists($xml_file))
				throw new Exception("XML-file '{$xml_file}' NOT exosts.");
		//include LIB_PATH . 'PHPExcel/Writer/Excel2007.php';
		$xml = new SimpleXMLElement(file_get_contents($xml_file));
		}
		elseif ($type == 'TEXT') {
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
		$this->parse_styles($xml->body->styles);
		//dbg::write($this->styles);
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
				$rowClass = (string)$tr['class'];
				$rowStyle = (!empty($rowClass) && !empty($this->styles[$rowClass])) ? $rowStyle = $this->styles[$rowClass] : array();

				foreach ($tr->td as $td)
				{
					if ($td['col'] > 0)
						$col = (int)$td['col'];
					// Пропускаем ячейки, которые были объеденены rowspan
					if ($this->merge[$row][$col] === true)
					{
						do
						{
							$col++;
						}
						while($this->merge[$row][$col] == true);
					}
					$current_cell = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,$row);

					//9* высота строки  выставляется по последне заданной высоте ячейки в строке
					if ($td['height'] > 0)
					{
						$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight($td['height']);
					}

					//9* <td width="200">  ширина колонны,  хинт - естественно будет та ширина которая в последней ячейке  колонный в шаблоне выставлена
					if ($td['width'] > 0)
					{
						$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth($td['width']);
					}
					
					// AS <td number_format="# ###,0">
					if ($td['number_format'] != "")
					{
						$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getNumberFormat()->setFormatCode((string)$td['number_format']);
					}

					//=== Подготовка стиля
					// Если указано имя стиля
					$cellClass = (string)$td['class'];
					$cellStyle = (!empty($cellClass) && !empty($this->styles[$cellClass])) ? $cellStyle = $this->styles[$cellClass] : array();
					// Если стиля для ячейки нет, но определён для строки
					if (empty($cellStyle)) $cellStyle = $rowStyle;
					// Собираем дополн. стиль из ячейки
					$cellStyle = $this->prepare_style($td, $cellStyle);
					// Применяем стиль
					//dbg::write($cellStyle);
					$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($cellStyle);

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, (string) $td);

					if ($td['merge'] > 0)
					{
						$merge_to_cell =  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col+$td['merge'],$row);
						$coord1 = $current_cell->getCoordinate();
						$coord2 = $merge_to_cell->getCoordinate();
						$objPHPExcel->getActiveSheet()->mergeCells("$coord1:$coord2");
						$start_merge_coulumn = $col;
						$col = $col+$td['merge'];
						//9* ниже надо чтобы бордер  райт у последней замерженой ячейки также выставился
						
						if ($td['border'] > 0)
						{
							$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($cellStyle);
							//9* msExcel patch 
							for($i=$start_merge_column+1;$i<$col;$i++)
							{
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							}
						}
					}

					// Anthon S Litvinenko <a.litvinenko@web50.ru> - Если объединение только колонок
					if ($td['colspan'] > 0 && !$td['rowspan'])
					{
						$ocol = $col;
						$col = $col + ($td['colspan'] - 1);
						$merge_to_cell =  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row);
						$coord1 = $current_cell->getCoordinate();
						$coord2 = $merge_to_cell->getCoordinate();
						$objPHPExcel->getActiveSheet()->mergeCells("$coord1:$coord2");
						for ($c = $ocol + 1; $c < $ocol + $td['colspan']; $c++)
						{
							if ($c == ($ocol + $td['colspan'] - 1) && !empty($cellStyle['borders']['right']))
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $row)->getBorders()->getRight()->setBorderStyle($cellStyle['borders']['right']['style']);
							if (!empty($cellStyle['borders']['top']))
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $row)->getBorders()->getTop()->setBorderStyle($cellStyle['borders']['top']['style']);
							if (!empty($cellStyle['borders']['bottom']))
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $row)->getBorders()->getBottom()->setBorderStyle($cellStyle['borders']['bottom']['style']);
						}
					}
					// Anthon S Litvinenko <a.litvinenko@web50.ru> - Если объединение только строк
					else if ($td['rowspan'] > 0 && !$td['colspan'])
					{
						$merge_to_cell =  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row + ($td['rowspan'] - 1));
						$coord1 = $current_cell->getCoordinate();
						$coord2 = $merge_to_cell->getCoordinate();
						$objPHPExcel->getActiveSheet()->mergeCells("$coord1:$coord2");
						for ($r = $row + 1; $r < $row + $td['rowspan']; $r++)
						{
							$this->merge[$r][$col] = true;
						}
						if (!empty($cellStyle['borders']['bottom']))
							$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row + ($td['rowspan'] - 1))->getBorders()->getBottom()->setBorderStyle($cellStyle['borders']['bottom']['style']);
					}
					// Anthon S Litvinenko <a.litvinenko@web50.ru> - Если объединение и строк и колонок
					else if ($td['rowspan'] > 0 && $td['colspan'] > 0)
					{
						$merge_to_cell =  $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col + ($td['colspan'] - 1), $row + ($td['rowspan'] - 1));
						$coord1 = $current_cell->getCoordinate();
						$coord2 = $merge_to_cell->getCoordinate();
						$objPHPExcel->getActiveSheet()->mergeCells("$coord1:$coord2");
						for ($r = $row; $r < $row + $td['rowspan']; $r++)
						{
							for ($c = $col; $c < $col + $td['colspan']; $c++)
							{
								$this->merge[$r][$c] = true;
								if (!empty($cellStyle['borders']['bottom']))
									$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($c, $row + ($td['rowspan'] - 1))->getBorders()->getBottom()->setBorderStyle($cellStyle['borders']['bottom']['style']);
							}
							if (!empty($cellStyle['borders']['right']))
								$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col + ($td['colspan'] - 1), $r)->getBorders()->getRight()->setBorderStyle($cellStyle['borders']['right']['style']);
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

	protected function parse_styles($styles)
	{
		foreach ($styles->style as $style)
		{
			$name = (string)$style['name'];
			if (empty($name)) continue;
			$this->styles[$name] = $this->prepare_style($style, array());
		}
	}

	protected function prepare_style($style, $preStyle)
	{
		$styleArray = (!empty($preStyle)) ? $preStyle : array();

		//9* <td border="1"> //9* пока только бордер может быть равен 1, автоматом делает аутлайн и THIN  других бордеров пока не поддереживаем
		if ((string)$style['border'] === "1")
		{
			$defaults_border = array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000'),
			);
			$styleArray['borders'] = array(
				'top' => $defaults_border,
				'right' => $defaults_border,
				'bottom' => $defaults_border,
				'left' => $defaults_border,
			);
		}
		// AS <td border="1 0 0 1">
		else if (!empty($style['border']))
		{
			list($bt, $br, $bb, $bl) = array_map('intval', explode(' ', (string)$style['border']));
			$defaults_border = array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000'),
			);
			if ($bt) $styleArray['borders']['top'] = $defaults_border;
			if ($br) $styleArray['borders']['right'] = $defaults_border;
			if ($bb) $styleArray['borders']['bottom'] = $defaults_border;
			if ($bl) $styleArray['borders']['left'] = $defaults_border;
		}

		//9* <td bgcolor="ffffff"> Внимание -   цвета задается просто  RGB без #  например cccccc или  b0fecf  
		if (!empty($style['bgcolor']))
		{
			$styleArray['fill'] = array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => $style['bgcolor']),
			);
		
		}

		// 9* вертикальный  align
		if (!empty($style['valign']))
		{
			if ($style['valign'] == 'center')
				$styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_CENTER;

			if ($style['valign'] == 'top')
				$styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_TOP;

			if ($style['valign'] == 'bottom')
				$styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_TOP;

			if ($style['valign'] == 'justify')
				$styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_JUSTIFY;
		}

		//9* горизонтальный Align
		if (!empty($style['align']))
		{
			if ($style['align'] == 'center')
				$styleArray['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;

			if ($style['align'] == 'left')
				$styleArray['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_LEFT;

			if ($style['align'] == 'right')
				$styleArray['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;

			if ($style['align'] == 'justify')
				$styleArray['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY;
		}

		//9* <td bold="1">
		if ($style['bold'] != '')
		{
			$styleArray['font']['bold'] = true;
		}

		//9* <td color="ffffff"> set font-color
		if ($style['color'] != '')
		{
			$styleArray['font']['color'] = array('rgb' => $style['color']);
		}

		//9* <td font-name="Arial">
		if ($style['font-name'] != '')
		{
			$styleArray['font']['name'] = $style['font-name'];
		}
		else
		{
			$styleArray['font']['name'] = 'Arial';//9* Arial by default
		}

		//AS <td font-size="10">
		if ($style['font-size'] != '')
		{
			$styleArray['font']['size'] = intval($style['font-size']);
		}

		//9* <td wrap="1"> wraps it
		if ($style['wrap'] != '')
		{
			$styleArray['alignment']['wrap'] = true;
		}

		return (array)$styleArray;
	}
}
?>
