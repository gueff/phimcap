**this class provides a captcha image you could use in your own html forms.**

# Requirements

- Linux
- php >=7.4
  - GD Library extension
- existance of a true type font like `FreeMono.ttf`

---

## Install

~~~bash
{
  "require": {
    "gueff/phimcap":"1.0.*"
  }
}
~~~

---

## How to use 

### ... a `forgotPassword` - Example using [myMVC 3.2.x](https://mymvc.ueffing.net/)

assuming your primary working Module is called `Foo`:

#### 1) dealing with html form

_create a MIX `/forgotPassword/` route, leading to `\Foo\Controller\Index::forgotPassword`_
~~~php 
\MVC\Route::MIX(
    ['GET', 'POST'],
    '/forgotPassword/',
    '\Foo\Controller\Index::forgotPassword',
    $oDTRoutingAdditional
        ->set_sLayout($sTheme . '/Frontend/layout/index.tpl')
        ->set_sContent($sTheme . '/Frontend/content/forgotPassword.tpl')
        ->getPropertyJson()
);
~~~

_`\Foo\Controller\Index::forgotPassword`: create new captcha text & save to Session_
~~~php
public function forgotPassword()
{
    // grab for POSTed captcha; sanitize
    $sCaptcha = substr(
        preg_replace("/[^\\p{L}\\p{N}']+/u", '', get($_POST['captcha'])),
        0,
        strlen(Session::is()->get('mymvc.captcha'))
    );

    if (
        ($sCaptcha === Session::is()->get('mymvc.captcha'))
    )
    {
        // stuff...
    }

    // create new captcha text & save to session
    $sCaptchaText = \Phimcap::text();
    Session::is()->set('mymvc.captcha', $sCaptchaText);

    $sContent = $this->oView->loadTemplateAsString('/Frontend/content/forgotPassword.tpl');
    $this->oView->assign('sContent', $sContent);
}
~~~      

_template `/Frontend/content/forgotPassword.tpl`_  
~~~html
<form action="" method="post">
    <label for="captcha">Captcha</label>
    <img src="/captcha/">
    <input type="text"
           name="captcha"
           id="captcha" 
           class="form-control"
           value=""
           maxlength="5"
           placeholder="captcha code"
    >
</form>
~~~

#### 2) serving the captcha image

_create `\Foo\Controller\Index::captcha`_
~~~php 
public function captcha()
{
    \Phimcap::image(
        Session::is()->get('mymvc.captcha')
    );
}
~~~
- here the captcha text for the image is taken from session

_create a `/captcha/` route, leading to `\Foo\Controller\Index::captcha`_
~~~php
\MVC\Route::GET(
    '/captcha/',
    '\Foo\Controller\Index::captcha'
);
~~~

So the captcha image is callable via

~~~html
<img src="/captcha/">
~~~

---

### Customizing

#### using different Font

Phimcap uses `/usr/share/fonts/truetype/freefont/FreeMono.ttf`, 
which is likely to be installed by default on Ubuntu Linux systems.

If you want a different Font, add the absolute path as second parameter:

~~~php 
\Phimcap::image(
    Session::is()->get('mymvc.captcha'),
    '/usr/share/fonts/truetype/msttcorefonts/andalemo.ttf'
);
~~~


#### text length

per default the text length is set to `5`.

you can change it to a value between `5` up to `10` by adding a value as parameter.

~~~php
$sCaptchaText = \Phimcap::text(10);
~~~