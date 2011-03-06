<?php
/**
*	Библиотека для преобразования XML в MS Word
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class xml2doc
{
        public function __construct()
	{
        }

	public function generate($xml_file)
	{
		if (!file_exists($xml_file))
			throw new Exception("XML-file '{$xml_file}' NOT exosts.");

		$xml = new SimpleXMLElement(file_get_contents($xml_file));

		// Create a new PHPWord Object
		$objPHPWord = new PHPWord();

		// Every element you want to append to the word document is placed in a section. So you need a section:
		$section = $objPHPWord->createSection();

		foreach ($xml->body->p as $p)
		{
			$section->addText((string) $p);
			dbg::write((string) $p);
		}

		header('Content-Type: application/vnd.ms-word');
		header('Content-Disposition: attachment;filename="' . ((string) $xml->body['title']) . '.doc"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPWord_IOFactory::createWriter($objPHPWord, 'Word2007');
		$objWriter->save('php://output');
		exit;
	}
}
?>
