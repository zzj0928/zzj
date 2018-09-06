<?php

//excel导出
/**
 * 
 * @param type $head 头部导航
 * @param type $sheet_title sheet 名称
 * @param type $fileName  保存文件名
 * @param type $datas  数据
 * @param type $type 保存类型
 */
function exportExcel($head, $sheet_title, $fileName, $datas, $row_title, $type = '') {
    $datas = array(
            array('王城', '男', '18', '1997-03-13', '18948348924'),
            array('李飞虹', '男', '21', '1994-06-13', '159481838924'),
            array('王芸', '女', '18', '1997-03-13', '18648313924'),
            array('郭瑞', '男', '17', '1998-04-13', '15543248924'),
            array('李晓霞', '女', '19', '1996-06-13', '18748348924'),
        );
    
    $head = array("漏洞编号", "项目编号", "部门", "项目组", "漏洞名称", "危害等级", "漏洞分类", "状态", "提交人", "提交时间", "接口人", "修复人", "修复时间");
    $fileName = "漏洞详情" . date("Ymd", time());
    $sheet_title = "漏洞详情";
    $row_title = "漏洞详情";
    
    
    // 创建一个处理对象实例
    vendor("PHPExcel");
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objActSheet = $objPHPExcel->getActiveSheet();
    $objActSheet->setTitle($sheet_title);

    //由PHPExcel根据传入内容自动判断单元格内容类型
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
    $i = 'a';
    foreach ($head as $title) {
        $objActSheet->setCellValue($i . '2', $title);
        $objActSheet->getColumnDimension($i)->setWidth('15'); //设置列宽
        $objActSheet->getStyle($i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);       //垂直方向上中间居中
        $objActSheet->getStyle($i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);    //水平方向上对齐
        $i++;
    }
    $j = 3;
    foreach ($datas as $row) {
        $i = 'a';
        foreach ($row as $cell) {
            $objActSheet->setCellValue($i . $j, (string) $cell);
            $i++;
        }
        $j++;
    }
    //给第一行赋值
    $objActSheet->setCellValue('A1', (string) $row_title);
    //样式修改
    $objActSheet->mergeCells('A1:M1');      // A28:B28合并

    $objActSheet->getRowDimension("1")->setRowHeight(25); // 第一行的默认高度
    $objActSheet->getRowDimension("2")->setRowHeight(25); // 第一行的默认高度
    $objActSheet->getStyle("A2:M2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);       //垂直方向上中间居中
    $objActSheet->getStyle("A2:M2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);    //水平方向上对齐
    //设置填充颜色
    $objActSheet->getStyle('A2:M2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objActSheet->getStyle('A2:M2')->getFill()->getStartColor()->setARGB('EAC100');

    $objActSheet->getStyle('A2:M2')->getFont()->setBold(true);
    // 边框
  /*  $styleThinBlackBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN, //设置border样式
                //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                'color' => array('argb' => 'FF000000'), //设置border颜色
            ),
        ),
    );
    $objActSheet->getStyle('A3:M3')->applyFromArray($styleThinBlackBorderOutline);
*/
    ob_end_clean(); //清除缓冲区,避免乱码 
    if ($type == 'doc') {
        header('Content-Type: application/vnd.msword');
        header('Content-Disposition: attachment;filename="' . $fileName . '.doc"');
    } else {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
    }

    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objPHPExcel->createSheet();
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;

    /*
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
      //$objWriter->save("php://output"); //输出浏览器 */
}