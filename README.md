# cvmultiply

Multiply very large integer values

## About
This allows multiplication of values greater than the CPU or GPU can manage. On a 64-bit system that means values greater than (2^64)-1 (which is 18,446,744,073,709,551,615). Anything greater than this cannot be recognised properly as a number, and requires specialised software or libraries to perform equations. This code accepts the very large numbers (VLNs) as strings of digits, and performs multiplication on them using the cross-vertical method. It can, of course, accept smaller numbers. It only accepts positive integer values.

## Cross-Vertical
Using 'long-multiplication' requires one sub-result line per digit in the multiplier, and once all the sub-results have been resolved, they need to be added together.

The problem here is it is possible to run out of space. A second feature of this method is the final result is produced starting at the least significant digit and works its way up to the most significant digit.

Note that each digit in the multiplicand is multiplied by each digit in the multiplier, and the results are then added together. Doing so in a different order would eliminate the sub-results and produce a final result starting with the most significant digit first, and the least significant digit last. If you think about it, this would give you the ability to start calling out the answer before the equation is fully solved. Sweet.

That method is today known as the cross-or-vertical multiplication method, or simply `cross-vertical multiply`.

## The Code
I've written a script in PHP which encodes the method. Since computers can't handle numbers as large as the script, they are created as strings of digits, as is of course, the result. I chose PHP because code in this scripting language is extremely close to pseudo-code, and therefore is fairly easy for you to translate into your favourite programming language.

There is a test script which calculates the diameter of the visible universe in centimetres. The parameters to this test are not guaranteed to be 100% accurate, but based on those parameters the final result is accurate. You can get out a notepad and do the long-multiplication to check it if you want.

Even easier is the example given below, which is also demonstrated by the test script `simple.php`.

## How it works
Firstly, you need two numbers, so let's take a simple pair:

```
    1234 x 5678
```

For the purposes of demonstration, no digit is duplicated in both numbers. Now let's  put them one above the other as usual for long-multiplication:

```
    1234
  x 5678
  ------
```

Next, imagine a box around the first digits:

```
   +-+
   |1|234
   |5|678
   +-+
```

The rules of this method are simple and always based on the box.

    1. Within each box, there may be other boxes. Multiply the opposite corners of each box and add them together
    2. When there are no corners, multiply vertically

So in the first instance there are no 'corners' and we can only multiply vertically, so we get `5x1 = 5`. Now we extend the box to the right:

```
   +--+
   |12|34
   |56|78
   +--+
    5
```

This time we have corners, so we multiply and add:

```
    5 x 2 = 10
    6 x 1 =  6
    10 + 6 = 16
```

The sub-result (16) is greater than 9, causing an overflow. The way to deal with overflows is simple:

```
    when dealing with overflows, just append a 0:
    5 -> 50
    add the result:
    50 + 16 = 66

    Everything should now look like this:

   +--+
   |12|34
   |56|78
   +--+
    66
```

In fact, a good rule of thumb is to always append a zero to the result, then add the answer. This even works with the first result: `0+5=5`.

We extend the box again and repeat the process:

```
   +---+
   |123|4
   |567|8
   +---+
    66
```

This time we get `5x3 = 15` and `7x1 = 7`, and add these together: `15+7 = 22`. Note though, the digits 2 and 6 are in the box and haven't been multiplied. They don't form a mini-box, so we multiply them vertically. Then we add all the results: `5x3=15, 7x1=7,6x2=12: 15+7+12 = 34`

Again there is an overflow to be added to the previous answer, so our solution now becomes `660+34 = 694`. Extend the box again:

```
   +----+
   |1234|
   |5678|
   +----+
    694
```

Multiplying the box corner we get `5x4=20, 8x1=8`. Note that the remaining numbers, `23` and `67` also form a box, so we can cross multiply them, so our solution looks like: `5x4=20, 8x1=8, 6x3=18, 7x2=14` giving `20+8+18+14=60`.

```
   +----+
   |1234|
   |5678|
   +----+
    7000
```

We've extended the box as far as it will go, so now we start to shrink it from the left:

```
     +---+
    1|234|
    5|678|
     +---+
    7000
```

Multiply the corners again and the vertical and add the results: `6x4=24`, `8x2=16`, `7x3=21` gives`24+16+21=61`. Append a zero and add the result to give `70061`. You should be ahead of me by now but here's the setup after we shrink the box again:

```
      +--+
    12|34|
    56|78|
      +--+
    70061
```

This gives `7x4=28` plus `8x3=24` giving `52`. Append the zero, add the result and our running total becomes `700662`, then shrink the box again:

```
       +-+
    123|4|
    567|8|
       +-+
    700662
```

Again, there are no corners, so we simply multiply vertically (`4x8=32`). Append the zero and add the result:

```
    1234
    5678
    -------
    7006652
```

There is a good explanation with diagrams of how this works here: [https://www.youtube.com/watch?v=c653D8F6pzk](https://www.youtube.com/watch?v=c653D8F6pzk)

For a deeper explanation on boxes within boxes, consider the following:

```

    12345678
  x 87654321
```

When our box reaches the maximum extent, there will be inner boxes too.

```
    +-------+
    |1234567|
    |8765432|
    +-------+
```

We already know that `8x7` and `2x1` are the corners of the primary box. The first inner box is formed by the next inward numbers, which are `7x6` and `3x2`. Then we imagine another box by stepping inward again, so `6x5` and `4x3`. This consumes all the numbers except `5x4` which are vertically aligned, so we multiply them. So the result of this box (as drawn) is:

```
    8x7 = 56 +
    2x1 =  2 +
    7x6 = 42 +
    3x2 =  6 +
    6x5 = 30 +
    4x3 = 12 +
    5x4 = 20
    ---------
         168
```
