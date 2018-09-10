<?php
	function getAgeGroup($dob){
    $dob = date("Y") - date('Y',$dob);
    $agegroup = "VAL";
    switch ($dob){
      case 7:
      case 8:
      case 9: $agegroup = "BEN";
        break;
      case 10:
      case 11: $agegroup = "INF";
        break;
      case 12:
      case 13: $agegroup = "INI";
        break;
      case 14:
      case 15: $agegroup = "JUV";
        break;
      case 16:
      case 17: $agegroup = "CAD";
        break;
      case 18:
      case 19: $agegroup = "JUN";
        break;
      case 20:
      case 21:
      case 22:
      case 23:
      case 24: $agegroup = "20-24";
        break;
      case 25:
      case 26:
      case 27:
      case 28:
      case 29: $agegroup = "25-29";
        break;
      case 30:
      case 31:
      case 32:
      case 33:
      case 34: $agegroup = "30-34";
        break;
      case 35:
      case 36:
      case 37:
      case 38:
      case 39: $agegroup = "35-39";
        break;
      case 40:
      case 41:
      case 42:
      case 43:
      case 44: $agegroup = "40-44";
        break;
      case 45:
      case 46:
      case 47:
      case 48:
      case 49: $agegroup = "45-49";
        break;
      case 50:
      case 51:
      case 52:
      case 53:
      case 54: $agegroup = "50-54";
        break;
      case 55:
      case 56:
      case 57:
      case 58:
      case 59: $agegroup = "55-59";
        break;
      case 60:
      case 61:
      case 62:
      case 63:
      case 64: $agegroup = "60-64";
        break;
      case 65:
      case 66:
      case 67:
      case 68:
      case 69: $agegroup = "65-69";
        break;
      case 70:
      case 71:
      case 72:
      case 73:
      case 74: $agegroup = "70-74";
        break;
      case 75:
      case 76:
      case 77:
      case 78:
      case 79: $agegroup = "75-79";
        break;
      case 80:
      case 81:
      case 82:
      case 83:
      case 84: $agegroup = "80-84";
        break;
      case 85:
      case 86:
      case 87:
      case 88:
      case 89: $agegroup = "85-89";
        break;
      case 90:
      case 91:
      case 92:
      case 93:
      case 94: $agegroup = "90-94";
        break;
      case 95:
      case 96:
      case 97:
      case 98:
      case 99: $agegroup = "95-99";
        break;
    }
    return $agegroup;
  }
?>