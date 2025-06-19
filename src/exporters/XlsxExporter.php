<?php

namespace hiqdev\yii2\export\exporters;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class XlsxExporter extends AbstractExporter implements ExporterInterface
{
    /**
     * Render file content
     *
     * @return string
     */
    public function export($gird)
    {
        $this->initExportOptions($gird);

        $writer = WriterEntityFactory::createXLSXWriter();
        ob_start();
        $this->writer->openToBrowser('php://output');

        $rows = [];
        //header
        $headerRow = $this->generateHeader();
        if (!empty($headerRow)) {
            $rows[] = WriterEntityFactory::createRowFromArray($headerRow);
        }

        //body
        $bodyRows = $this->generateBody();
        foreach ($bodyRows as $row) {
            $rows[] = WriterEntityFactory::createRowFromArray($row);
        }

        //footer
        $footerRow = $this->generateFooter();
        if (!empty($footerRow)) {
            $rows[] = WriterEntityFactory::createRowFromArray($footerRow);
        }

        $this->writer->addRows($rows);
        $this->writer->close();

        return $result;
    }
}
