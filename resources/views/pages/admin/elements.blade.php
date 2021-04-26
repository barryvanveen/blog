@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => new App\Domain\Utils\MetaData(
    'Elements',
    'all elements on 1 page',
    '/admin/elements',
    App\Domain\Utils\MetaData::TYPE_ARTICLE
)])
@endsection

@section('bgImage')
    <div class="-mt-16 block h-48 bg-repeat bg-image-1"></div>
@endsection

@section('bodyHeader')
    <article class="" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/BlogPosting">
        <header class="-mt-32 bg-gray-100 p-8 pb-4">
            <h1 class="mt-0 mb-4" itemprop="headline">
                My title with a slightly longer texts so it takes more than a single line?
            </h1>
            <p class="text-gray-700 text-sm">
                <time datetime="2019-12-30T21:00:00+01:00" itemprop="datePublished">Dec 30, 2019</time>
                <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
            </p>
        </header>
@endsection

@section('body')
        <div itemprop="articleBody">
            <p>The last article was all about <a href="https://barryvanveen.nl/blog/49-what-is-a-command-bus-and-why-should-you-use-it">the command bus</a>, a specific type of service bus. Now, let's take a step back and look at some other service buses. What similarities can we discover and how do they differ.</p>
            <h2>What is a service bus?</h2>
            <p>Let me try to <code>summarize what a service bus</code> is in my own words:</p>
            <ul>
                <li>A service bus is a way of exchanging messages between components.</li>
                <li>Messages are <a href="https://en.wikipedia.org/wiki/Data_transfer_object">DTO's</a> that contain information to act on.</li>
                <li>The "sender component" creates the message and passes it to the bus.</li>
            </ul>

            <h2>Making the change</h2>
            <p>It was actually quite simple, there are just a few protected attributes you have to change on the Eloquent model(s), as can be seen in this code sample:</p>

            <h2>Javascript Code</h2>
            <pre><code class="language-javascript hljs"><span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">$initHighlight</span>(<span class="hljs-params">block, cls</span>) </span>{
  <span class="hljs-keyword">try</span> {
    <span class="hljs-keyword">if</span> (cls.search(<span class="hljs-regexp">/\bno\-highlight\b/</span>) != -<span class="hljs-number">1</span>)
      <span class="hljs-keyword">return</span> process(block, <span class="hljs-literal">true</span>, <span class="hljs-number">0x0F</span>) +
             <span class="hljs-string">` class="<span class="hljs-subst">${cls}</span>"`</span>;
  } <span class="hljs-keyword">catch</span> (e) {
    <span class="hljs-comment">/* handle exception */</span>
  }
  <span class="hljs-keyword">for</span> (<span class="hljs-keyword">var</span> i = <span class="hljs-number">0</span> / <span class="hljs-number">2</span>; i &lt; classes.length; i++) {
    <span class="hljs-keyword">if</span> (checkCondition(classes[i]) === <span class="hljs-literal">undefined</span>)
      <span class="hljs-built_in">console</span>.log(<span class="hljs-string">'undefined'</span>);
  }

  <span class="hljs-keyword">return</span> (
    <span class="xml"><span class="hljs-tag">&lt;<span class="hljs-name">div</span>&gt;</span>
      <span class="hljs-tag">&lt;<span class="hljs-name">web-component</span>&gt;</span>{block}<span class="hljs-tag">&lt;/<span class="hljs-name">web-component</span>&gt;</span>
    <span class="hljs-tag">&lt;/<span class="hljs-name">div</span>&gt;</span></span>
  )
}

<span class="hljs-keyword">export</span>  $initHighlight;
</code></pre>

            <h2>PHP code</h2>
            <pre><code class="language-php hljs"><span class="hljs-keyword">require_once</span> <span class="hljs-string">'Zend/Uri/Http.php'</span>;

<span class="hljs-keyword">namespace</span> <span class="hljs-title">Location</span>\<span class="hljs-title">Web</span>;

<span class="hljs-class"><span class="hljs-keyword">interface</span> <span class="hljs-title">Factory</span>
</span>{
    <span class="hljs-built_in">static</span> <span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">_factory</span>(<span class="hljs-params"></span>)</span>;
}

<span class="hljs-keyword">abstract</span> <span class="hljs-class"><span class="hljs-keyword">class</span> <span class="hljs-title">URI</span> <span class="hljs-keyword">extends</span> <span class="hljs-title">BaseURI</span> <span class="hljs-keyword">implements</span> <span class="hljs-title">Factory</span>
</span>{
    <span class="hljs-keyword">abstract</span> <span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">test</span>(<span class="hljs-params"></span>)</span>;

    <span class="hljs-keyword">public</span> <span class="hljs-built_in">static</span> <span class="hljs-variable">$st1</span> = <span class="hljs-number">1</span>;
    <span class="hljs-keyword">const</span> ME = <span class="hljs-string">"Yo"</span>;
    <span class="hljs-keyword">var</span> <span class="hljs-variable">$list</span> = <span class="hljs-literal">NULL</span>;
    <span class="hljs-keyword">private</span> <span class="hljs-variable">$var</span>;

    <span class="hljs-comment">/**
     * Returns a URI
     *
     * <span class="hljs-doctag">@return</span> URI
     */</span>
    <span class="hljs-built_in">static</span> <span class="hljs-keyword">public</span> <span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">_factory</span>(<span class="hljs-params"><span class="hljs-variable">$stats</span> = <span class="hljs-keyword">array</span>(<span class="hljs-params"></span>), <span class="hljs-variable">$uri</span> = <span class="hljs-string">'http'</span></span>)
    </span>{
        <span class="hljs-keyword">echo</span> <span class="hljs-keyword">__METHOD__</span>;
        <span class="hljs-variable">$uri</span> = explode(<span class="hljs-string">':'</span>, <span class="hljs-variable">$uri</span>, <span class="hljs-number">0b10</span>);
        <span class="hljs-variable">$schemeSpecific</span> = <span class="hljs-keyword">isset</span>(<span class="hljs-variable">$uri</span>[<span class="hljs-number">1</span>]) ? <span class="hljs-variable">$uri</span>[<span class="hljs-number">1</span>] : <span class="hljs-string">''</span>;
        <span class="hljs-variable">$desc</span> = <span class="hljs-string">'Multi
line description'</span>;

        <span class="hljs-comment">// Security check</span>
        <span class="hljs-keyword">if</span> (!ctype_alnum(<span class="hljs-variable">$scheme</span>)) {
            <span class="hljs-keyword">throw</span> <span class="hljs-keyword">new</span> Zend_Uri_Exception(<span class="hljs-string">'Illegal scheme'</span>);
        }

        <span class="hljs-keyword">$this</span>-&gt;var = <span class="hljs-number">0</span> - <span class="hljs-built_in">self</span>::<span class="hljs-variable">$st</span>;
        <span class="hljs-keyword">$this</span>-&gt;list = <span class="hljs-keyword">list</span>(<span class="hljs-keyword">Array</span>(<span class="hljs-string">"1"</span>=&gt; <span class="hljs-number">2</span>, <span class="hljs-number">2</span>=&gt;<span class="hljs-built_in">self</span>::ME, <span class="hljs-number">3</span> =&gt; \Location\Web\URI::class));

        <span class="hljs-keyword">return</span> [
            <span class="hljs-string">'uri'</span>   =&gt; <span class="hljs-variable">$uri</span>,
            <span class="hljs-string">'value'</span> =&gt; <span class="hljs-literal">null</span>,
        ];
    }
}</code></pre>

            <h2>Results</h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td></td>
                    <td><strong>Method #1</strong></td>
                    <td><strong>Method #2</strong></td>
                    <td><strong>Method #3</strong></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><em>1 attribute</em></td>
                    <td>0.190 </td>
                    <td>0.139 (<span class="text-success">-27%</span>)</td>
                    <td>0.072 (<span class="text-success">-62%</span>)</td>
                </tr>
                <tr>
                    <td><em>2 attributes</em></td>
                    <td>0.499 </td>
                    <td>0.372 (<span class="text-success">-25%</span>)</td>
                    <td>0.196 (<span class="text-success">-61%</span>)</td>
                </tr>
                <tr>
                    <td><em>3 attributes</em></td>
                    <td>0.488</td>
                    <td>0.603 (<span class="text-danger">+25%</span>)</td>
                    <td>0.198 (<span class="text-success">-59%</span>)</td>
                </tr>
                </tbody>
            </table>

            <h2>Configure GTM</h2>
            <p>Now it's time to login into Google Tag Manager and configure our variables, triggers and tags:</p>

            <strong>Make a new user-defined variable called "errorcode".</strong>
            <figure>
                <a href="images/gtm-define-datalayer-variable.png" title="View the full sized image" target="_blank">
                    <img src="/images/gtm-define-datalayer-variable.png?w=320" alt="Create a new DataLayer variable"></a>
                <figcaption>Create a new DataLayer variable</figcaption>
            </figure>
            <p>Set an empty default value (ie. "") so that you can be sure the variable exists on every page.</p>

            <strong>Create a new trigger called "Error code on page".</strong>
            <figure>
                <a href="images/gtm-define-errorcode-trigger.png" title="View the full sized image" target="_blank">
                    <img src="images/gtm-define-errorcode-trigger.png?w=320" alt="Create a new trigger that triggers when there is an error code on the page"></a>
                <figcaption>Create a trigger for when the page contains an error code</figcaption>
            </figure>
            <p>This trigger will, ehm, trigger when the `errorcode` variable is non-zero.</p>

            <strong>Add a new tag called for tracking events when there is an errorcode on a page.</strong>

            <figure>
                <a href="images/gtm-define-tag-for-errorcode-events.png" title="View the full sized image" target="_blank">
                    <img src="images/gtm-define-tag-for-errorcode-events.png?w=320" alt="Create a new tag for registering error page events"></a>
                <figcaption>Create a tag to track events</figcaption>
            </figure>
            <p>This tag uses our new variable as the name of our event. It is only called when our newly defined trigger matches.</p>

            <p><strong>Hope this solved your problem too. I've only tested these solutions on Laravel Homestead but they might apply to other setups. Leave me a comment if you have questions or suggestions.</strong></p>
        </div>
    </article>

    <div class="mt-8">
        <div class="h-32 w-full bg-repeat bg-image-1"></div>
        <div class="h-32 w-full bg-repeat bg-image-2"></div>
        <div class="h-32 w-full bg-repeat bg-image-3"></div>
        <div class="h-32 w-full bg-repeat bg-image-4"></div>
        <div class="h-32 w-full bg-repeat bg-image-5"></div>
    </div>

    {{-- disabled submit button --}}
    <input class="cursor-not-allowed bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer"
           type="submit"
           name="foo"
           value="submit">
@endsection
