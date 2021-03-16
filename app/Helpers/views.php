<?php

/**
 * Calculate the growth between two values
 *
 * @param $current
 * @param $previous
 * @return array|int
 */
function calcGrowth($current, $previous)
{
    if ($previous == 0 || $previous == null || $current == 0) {
        return 0;
    }

    return $result = (($current - $previous) / $previous * 100);
}

/**
 * Returns the discount amount
 * Amount * Discount%
 *
 * @param $amount
 * @param $discount
 * @return float|int
 */
function checkoutDiscount($amount, $discount)
{
    return $amount * ($discount / 100);
}

/**
 * Returns the amount after discount
 * Amount - Discount$
 *
 * @param $amount
 * @param null $discount
 * @return float|int
 */
function checkoutPostDiscount($amount, $discount = null)
{
    return $discount ? $amount - $discount : $amount;
}

/**
 * Returns the inclusive tax amount
 * PostDiscount - PostDiscount / (1 + TaxRate)
 *
 * @param $amount
 * @param $taxRate
 * @return float|int
 */
function checkoutInclusiveTax($amount, $taxRate)
{
    return $amount - ($amount / (1 + ($taxRate / 100)));
}

/**
 * Returns the amount after discount and included taxes
 * PostDiscount - InclusiveTax$
 *
 * @param $amount
 * @param null $inclusiveTax
 * @return float|int
 */
function checkoutPostDiscountLessInclTax($amount, $inclusiveTax = null)
{
    return $inclusiveTax ? $amount - $inclusiveTax : $amount;
}

/**
 * Returns the exclusive tax amount
 * PostDiscLessIncTax * TaxRate
 *
 * @param $amount
 * @param $taxRate
 * @return float|int
 */
function checkoutExclusiveTax($amount, $taxRate)
{
    return $amount * ($taxRate / 100);
}

/**
 * Calculate the total, including the exclusive taxes
 * PostDiscount + ExclTax$
 *
 * @param $amount
 * @param null $exclusiveTax
 * @return mixed
 */
function checkoutTotal($amount, $exclusiveTax = null)
{
    return $exclusiveTax ? $amount + $exclusiveTax : $amount;
}