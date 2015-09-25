# ivt/assert

PHP assertions library

## Example Usage

1. Checking output of functions that return `false` or `null` on error
    ```php
    use IVT\Assert;
    
    // good (may silently fail depending on the error handler)
    
    $handle = fopen('/my/file', 'rb');
    $data = fread($handle, 100);
    fclose($handle);
    
    // better (verbose)
    
    $handle = fopen('/my/file', 'rb');
    if ($handle === false)
        throw new Exception('fopen() failed');
    $data = fread($handle, 100);
    if ($data === false)
        throw new Exception('fread() failed');
    if (fclose($handle) === false)
        throw new Exception('fclose() failed');
    
    // best (will fail regardless of the error handler)
    
    Assert::resource($handle = fopen('/my/file', 'rb'));
    Assert::string($data = fread($handle, 100));
    Assert::true(fclose($handle));
    ```

2. Tests

    ```php
    function humanize_bytes($n) {
        $i = (int)log(max(abs($n), 1), 1000);
        $p = 'KMGTPEZY';
        if ($i == 0)
            return "$n B";
        else
            return number_format($n / pow(1000, $i), 1) . " {$p[$i - 1]}B";
    }

    function humanize_bytes_test() {
        Assert::equal(humanize_bytes(1), '1 B');
        Assert::equal(humanize_bytes(-1402), '-1.4 KB');
        // ...
    }
    ```

3. Checking invariants
    ```php
    Assert::int($object->getId()); // Object must have ID
    ```

4. etc
