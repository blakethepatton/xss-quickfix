<script>alert('xss')</script>
> <script>alert('blockquote')</script> 

`<script>alert('code tag')</script> 
`<script>alert('code tag')</script>`

```<html>tag</html>```
```javascript
    <script>alert('non-encoded tags')</script>
    $response = $parsedown->parse($autoEmbed->parse($content));
    route.get('/', (req, res) => {
        alert('hello');
    });
    &lt;script>alert('blahahahal')&lt;/script>
    &amp&lt;
    &gt;

```
```markup 
&lt;script src="hello.js">&lt;/script> 
``` 
&lt;script>alert('blahahahal')&lt;/script>

%3cscript %3e alert('hello1'); %3c/script%3e

\x3cscript\x3e alert('hello2') \x3c/script\x3e

[hi there](javascript:alert('hi there'))
> block quote
> 
> that continues
> 
> on multiple lines
> 
> > and also has a quote
> > 
> > within the quote
> > 
> > > sorta like quoteception
> > > 
> > > > 🤔

``` 
    <img src="http://placekitten.com/50/50" alt='kitten'>
    <img src="http://placekitten.com/50/50">
    <a href="http://placekitten.com/">Localhost</a>
```

<img src="https://placekitten.com/50/50" onerror="alert(/DOM-XSS/)" alt="image alt">
<img src="https://placekitten.com/50/50">

<img src="/" onerror="alert(/DOM-XSS/)" alt="hello">

<a href="https://blakethepatton.com">My website</a>

<a href="javascript:alert('hello')">Hello</a>

<img src="javascript:alert('hello')" alt='hello'>

[hello](JavaScript:alert('hello'))

![hello](JavaScript:alert('hello'))

<IMG SRC=`javascript:alert("RSnake says, 'XSS'")`>

```html
    <div>
        <a href="javascript:alert('hello')">Good day</a>
    </div>
```