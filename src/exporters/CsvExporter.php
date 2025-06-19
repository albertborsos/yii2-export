<?php

namespace hiqdev\yii2\export\exporters;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class CsvExporter extends AbstractExporter implements ExporterInterface
{
    protected $writer;

    /**
     * Render file content
     *
     * @param $gird
     * @return string
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export($gird): string
    {
        $this->initExportOptions($gird);

        $this->writer = WriterEntityFactory::createCSVWriter();
        $this->applySettings();
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

        return ob_get_clean();
    }

    protected function applySettings()
    {
        $this->writer->setFieldDelimiter($this->settings['fieldDelimiter']);
        $this->writer->setFieldEnclosure($this->settings['fieldEnclosure']);
    }
}
