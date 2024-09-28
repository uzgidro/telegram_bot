<?php

namespace App\Constants;

use App\Models\InlineButton;

class Buttons
{
   public static function getHomeButton(string $language) : array
   {
       switch ($language) {
           case Languages::RU:
           {
               $text = '🔙 На главную';
               break;
           }
           case Languages::UZ:
           {
               $text = '🔙 Bosh sahifaga';
               break;
           }
           default:
           {
               $text = '🔙 Home';
               break;
           }
       }
       return (new InlineButton($text, CallbackData::CANCEL))->toArray();
   }

   public static function getCancelButton(string $language) : array
   {
       switch ($language) {
           case Languages::RU:
           {
               $text = '🔙 Отмена';
               break;
           }
           case Languages::UZ:
           {
               $text = '🔙 Bekor qilish';
               break;
           }
           default:
           {
               $text = '🔙 Cancel';
               break;
           }
       }
       return (new InlineButton($text, CallbackData::CANCEL))->toArray();
   }
}
