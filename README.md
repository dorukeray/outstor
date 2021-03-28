![Outsights Logo](assets/outsights-dark.png)

# Outstor

## What?

Outstor is a simple and lightweight storage library for PHP. <br>Outstor is a component of the Outsights framework, but also independent, so anyone can use it.

## Why?

Because writing storage-layer code in vanilla PHP is really frustrating. It's too much burden on the developer. But also using a framework just to make the work easy is overkill. So we thought it would be great to have a storage library 

## How?

Simple. Use your favorite dependency util and autoload the `Dorkodu\Outstor` namespace.

If want to see everything you need, check out the [docs](DOCS.md).

### **It has a few components, more on the way :**

- **DB :** a PDO wrapper with SQL query building.
- **FileStorage :** useful and intuitive API for file system operations.

### Here is a sample :

```php
class SampleTest extends Seekr 
{
  // This test is designed to succeed
  public function testOne()
  {
    Say::equal( 1, 1 );
  }
  
  // This test is designed to fail
  public function testTwo()
  {
    Say::equal( 1, 2 );
  }
}
```

## Author

Doruk Eray : [GitHub](https://github.com/dorukdorkodu)  | [Twitter](https://twitter.com/dorkodu) | [doruk@dorkodu.com](mailto:doruk@dorkodu.com) | [dorkodu.com](https://dorkodu.com)

See also the list of [contributions](https://libre.dorkodu.com) that we are making at [Dorkodu](dorkodu.com) to the free software community.

## License

Outstor is open-sourced software licensed under the [MIT license](LICENSE).

