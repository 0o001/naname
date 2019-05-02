<?php

/*
FULL_WORD (0): Tam kelime (örn. merhaba, naber)
FINDING_WORD (1): İçinde geçen kelime (örn. senden çok çok çok hoşlanıyorum -> hoşlanıyorum)
ADDITIONAL_WORD (2): Ekler (örn. iyi misin?, olsun mu?)
*/
abstract class AnswerType
{
    const FULL_WORD = 0;
    const FINDING_WORD = 1;
    const ADDITIONAL_WORD = 2;

}

?>