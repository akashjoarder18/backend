<?php
require_once 'classes/FontFamily.php';
require_once 'classes/FontGroup.php';
require 'vendor/autoload.php';

use FontLib\Font;


// CORS and JSON headers
header("Access-Control-Allow-Origin: *"); // Allow all origins, or specify http://localhost:3000
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$action = $_POST['action'] ?? '';

if ($action == 'OPTIONS') {
    http_response_code(200); // Send a 200 OK for OPTIONS preflight requests
    exit(); // End the script here for preflight requests
} elseif ($action == 'upload') {
    $font = new FontFamily();
    echo json_encode($font->upload($_FILES['font']));
} elseif ($action == 'delete_uploaded_font') {
    $fontFileName = $_POST['font_file_name'] ?? null;
    $font = new FontFamily();
    echo json_encode($font->deleteUploadFile($fontFileName));
} elseif ($action === 'get_font_lists') {
    // Example: List fonts from the 'uploads/' directory
    $fonts = [];
    $dir = 'uploads/';

    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            $i = 0;
            while (($file = readdir($dh)) !== false) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'ttf') {

                    $font = Font::load('uploads/' . $file);
                    $font->parse();

                    // Extract font data
                    $fontName = $font->getFontName();
                    $fontFullName = $font->getFontFullName();
                    $fontType = $font->getFontType();
                    $fontFamily = $font->getFontSubfamily();
                    $fonts[$i]['id'] = $file;
                    $fonts[$i]['fontName'] = $fontName;
                    $fonts[$i]['fontFullName'] = $fontFullName;
                    $fonts[$i]['fontType'] = $fontType;
                    $fonts[$i]['fontFamily'] = $fontFamily;
                    $i++;
                }

            }
            closedir($dh);
        }
    }

    // Send JSON response
    echo json_encode($fonts);
    exit();

} elseif ($action === 'get_fonts') {
    // Example: List fonts from the 'uploads/' directory
    $fonts = [];
    $dir = 'uploads/';

    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'ttf') {

                    $font = Font::load('uploads/' . $file);
                    $font->parse();

                    // Extract font data
                    $fontName = $font->getFontName();
                    $fontFullName = $font->getFontFullName();
                    $fontSubfamily = $font->getFontSubfamily();
                    $fontType = $font->getFontType();
                    $fonts[] = $fontFullName;
                }
            }
            closedir($dh);
        }
    }

    // Send JSON response
    echo json_encode($fonts);
    exit();

} elseif ($action == 'create_group') {
    $fontGroup = new FontGroup();
    $fonts = $_POST['fonts'] ?? [];
    $fontG = $_POST['fontGroup'] ?? [];
    echo json_encode($fontGroup->createGroup($fontG, $fonts));
} elseif ($action == 'get_groups') {
    $fontGroup = new FontGroup();
    echo json_encode($fontGroup->getAllGroups());
} elseif ($action == 'delete_group') {
    $fontGroup = new FontGroup();
    $groupId = $_POST['id'];
    echo json_encode($fontGroup->deleteGroup($groupId));
}
