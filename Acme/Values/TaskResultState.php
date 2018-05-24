<?php

namespace Acme\Values;

class TaskResultState
{
    const NOT_EXECUTED = 0;
    const SUCCESS = 9;
    const IMPOSSIBLE = 8;
    const TECHNICAL_FAIL = 7;
    const LOGIC_FAIL = 6;
    const UNEXPECTED_FAIL = 5;
}
