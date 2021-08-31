<?php


namespace App\Service;


class DateFr
{
    public function moisFr($value)
    {

        switch ($value) {
            case 1:
                $moisFr = "Janvier";
                break;
            case 2:
                $moisFr = "Février";
                break;
            case 3:
                $moisFr = "Mars";
                break;
            case 4:
                $moisFr = "Avril";
                break;
            case 5:
                $moisFr = "Mai";
                break;
            case 6:
                $moisFr = "Juin";
                break;
            case 7:
                $moisFr = "Juillet";
                break;
            case 8:
                $moisFr = "Août";
                break;
            case 9:
                $moisFr = "Septembre";
                break;
            case 10:
                $moisFr = "Octobre";
                break;
            case 11:
                $moisFr = "Novembre";
                break;
            case 12:
                $moisFr = "Décembre";
                break;
            default;
        }

        return $moisFr;
    }

    public function moisFr2($mois) {
        $moisArray = [
            "Janv",
            "Fev",
            "Mars",
            "Avril",
            "Mai",
            "Juin - ",
            "Juillet",
            "Aout",
            "Sept",
            "Oct",
            "Nov",
            "Dec"
        ];

        foreach($moisArray as $key => $value) {
            if($key + 1 == $mois) {
                $moisFr = $value;
                return $moisFr;
            }
        }
    }


    public function diplayDateFr(string $fr, $valueMonth) {

        if($fr === "fr2") {
            $moisReturn = $this->moisFr2($valueMonth->format('m'));
            return $valueMonth->format('d') . " " . $moisReturn . " " . $valueMonth->format('Y');
        } else {
            $moisReturn = $this->moisFr($valueMonth->format('m'));
            return $valueMonth->format('d') . " " . $moisReturn . " " . $valueMonth->format('Y');
        }
    }
}


//namespace App\Service;
//
//class DateFr
//{
//
//
//    public function moisFr($mois)
//    {
//
//        switch ($mois) {
//
//            case 1 :
//                $moisFr = "janvier";
//                break;
//
//            case 2 :
//                $moisFr = "février";
//                break;
//
//            case 3 :
//                $moisFr = "mars";
//                break;
//
//            case 4 :
//                $moisFr = "avril";
//                break;
//
//            case 5 :
//                $moisFr = "mai";
//                break;
//
//            case 6 :
//                $moisFr = "juin";
//                break;
//
//            case 7 :
//                $moisFr = "juillet";
//                break;
//
//            case 8 :
//                $moisFr = "août";
//                break;
//
//            case 9 :
//                $moisFr = "septembre";
//                break;
//
//            case 10 :
//                $moisFr = "octobre";
//                break;
//
//            case 11 :
//                $moisFr = "novembre";
//                break;
//
//            case 12 :
//                $moisFr = "décembre";
//                break;
//
//            default;
//
//
//        }
//
//
//        return $moisFr;
//
//
//    }
//
//
//    public function moisFr2($mois)
//    {
//        $moisArray = [
//            "janvier",
//            "février",
//            "mars",
//            "avril",
//            "mai",
//            "juin",
//            "juillet",
//            "août",
//            "septembre",
//            "octobre",
//            "novembre",
//            "décembre"
//        ];
//
//        foreach ($moisArray as $key => $value) {
//
//            if ($key + 1 == $mois) {
//                $moisFr = $value;
//            }
//        }
//
//        return $moisFr;
//    }
//
//
//    public function moisFr3($dateObject)
//    {
//
//        $moisNum = $dateObject->format("m");
//
//        $moisArray = [
//            "janvier",
//            "février",
//            "mars",
//            "avril",
//            "mai",
//            "juin",
//            "juillet",
//            "août",
//            "septembre",
//            "octobre",
//            "novembre",
//            "décembre"
//        ];
//
//        foreach ($moisArray as $key => $value) {
//
//            if ($key + 1 == $moisNum) {
//                $moisFr = $value;
//            }
//        }
//
//
//        $newDate = $dateObject->format("d") . " " . $moisFr . " " . $dateObject->format("Y");
//
//
//        return $newDate;
//
//
//    }
//
//
//    public function moisFr4($dateObject, $time = null)
//    {
//
//        $moisNum = $dateObject->format("m");
//
//        $moisArray = [
//            "janvier",
//            "février",
//            "mars",
//            "avril",
//            "mai",
//            "juin",
//            "juillet",
//            "août",
//            "septembre",
//            "octobre",
//            "novembre",
//            "décembre"
//        ];
//
//        foreach ($moisArray as $key => $value) {
//
//            if ($key + 1 == $moisNum) {
//                $moisFr = $value;
//            }
//        }
//
//        $heure = "";
//
//        if ($time) {
//            $heure = " à " . $dateObject->format("H:i:s");
//        }
//
//        $newDate = $dateObject->format("d") . " " . $moisFr . " " . $dateObject->format("Y") . $heure;
//
//
//        return $newDate;
//
//
//    }
//
//
//    public function moisFr6($produitObject, $time = null)
//    {
//
//        $dateObject = $produitObject->getDateAt();
//
//        $moisNum = $dateObject->format("m");
//
//        $moisArray = [
//            "janvier",
//            "février",
//            "mars",
//            "avril",
//            "mai",
//            "juin",
//            "juillet",
//            "août",
//            "septembre",
//            "octobre",
//            "novembre",
//            "décembre"
//        ];
//
//        foreach ($moisArray as $key => $value) {
//
//            if ($key + 1 == $moisNum) {
//                $moisFr = $value;
//            }
//        }
//
//        $heure = "";
//
//        if ($time) {
//            $heure = " à " . $dateObject->format("H:i:s");
//        }
//
//        if ($dateObject->format("d") == 1) {
//            $jour = "1er";
//        } else {
//            $jour = $dateObject->format("d");
//        }
//
//        $produitObject->newDate = $jour . " " . $moisFr . " " . $dateObject->format("Y") . $heure;
//        return $produitObject;
//
//
//    }
//
//
//    public function moisFr5($produitsArray, $time = null)
//    {
//
//
//        //dd($produitsArray);
//
//        foreach ($produitsArray as $produitObject) {
//            $this->moisFr6($produitObject, $time);
//        }
//
//
//        return $produitsArray;
//
//
//    }
//
//
//}