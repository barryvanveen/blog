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

            <!-- Code php -->
            <pre class="  language-php"><code class="  language-php"><span class="token string single-quoted-string">'remote_mysql'</span> <span class="token operator">=&gt;</span> <span class="token punctuation">[</span>
    <span class="token string single-quoted-string">'driver'</span> <span class="token operator">=&gt;</span> <span class="token string single-quoted-string">'mysql'</span><span class="token punctuation">,</span>
    <span class="token string single-quoted-string">'host'</span> <span class="token operator">=&gt;</span> <span class="token function">env</span><span class="token punctuation">(</span><span class="token string single-quoted-string">'REMOTE_DB_HOST'</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">'127.0.0.1'</span><span class="token punctuation">)</span><span class="token punctuation">,</span>
    <span class="token string single-quoted-string">'port'</span> <span class="token operator">=&gt;</span> <span class="token function">env</span><span class="token punctuation">(</span><span class="token string single-quoted-string">'REMOTE_DB_PORT'</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">'13306'</span><span class="token punctuation">)</span><span class="token punctuation">,</span>
    <span class="token string single-quoted-string">'database'</span> <span class="token operator">=&gt;</span> <span class="token function">env</span><span class="token punctuation">(</span><span class="token string single-quoted-string">'REMOTE_DB_DATABASE'</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">'forge'</span><span class="token punctuation">)</span><span class="token punctuation">,</span>
    <span class="token string single-quoted-string">'username'</span> <span class="token operator">=&gt;</span> <span class="token function">env</span><span class="token punctuation">(</span><span class="token string single-quoted-string">'REMOTE_DB_USERNAME'</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">'forge'</span><span class="token punctuation">)</span><span class="token punctuation">,</span>
    <span class="token string single-quoted-string">'password'</span> <span class="token operator">=&gt;</span> <span class="token function">env</span><span class="token punctuation">(</span><span class="token string single-quoted-string">'REMOTE_DB_PASSWORD'</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">''</span><span class="token punctuation">)</span><span class="token punctuation">,</span>
    <span class="token comment">// ... </span>
<span class="token punctuation">]</span><span class="token punctuation">,</span>
</code></pre>

    <pre class="  language-php"><code class="  language-php"><span class="token keyword">class</span> Article <span class="token keyword">extends</span> <span class="token class-name">Model</span>
<span class="token punctuation">{</span>
    <span class="token comment">// column name of key</span>
    <span class="token keyword">protected</span> <span class="token variable">$primaryKey</span> <span class="token operator">=</span> <span class="token string single-quoted-string">'uuid'</span><span class="token punctuation">;</span>

    <span class="token comment">// type of key</span>
    <span class="token keyword">protected</span> <span class="token variable">$keyType</span> <span class="token operator">=</span> <span class="token string single-quoted-string">'string'</span><span class="token punctuation">;</span>

    <span class="token comment">// whether the key is automatically incremented or not</span>
    <span class="token keyword">public</span> <span class="token variable">$incrementing</span> <span class="token operator">=</span> <span class="token constant boolean">false</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
</code></pre>

    <pre class="  language-bash"><code class="  language-bash"><span class="token operator">&lt;</span>IfModule mod_rewrite.c<span class="token operator">&gt;</span>
    <span class="token operator">&lt;</span>IfModule mod_negotiation.c<span class="token operator">&gt;</span>
        Options -MultiViews
    <span class="token operator">&lt;</span>/IfModule<span class="token operator">&gt;</span>

    <span class="token comment"># set Expire and Cache Control headers for css and js</span>
    <span class="token operator">&lt;</span>IfModule mod_expires.c<span class="token operator">&gt;</span>
        ExpiresActive On
        ExpiresDefault <span class="token string">"access"</span>
        ExpiresByType text/css <span class="token string">"access plus 1 year"</span>
        ExpiresByType application/javascript <span class="token string">"access plus 1 year"</span>

        ExpiresByType font/truetype <span class="token string">"access plus 1 year"</span>
        ExpiresByType font/opentype <span class="token string">"access plus 1 year"</span>
        ExpiresByType application/x-font-woff <span class="token string">"access plus 1 year"</span>
        ExpiresByType image/svg+xml <span class="token string">"access plus 1 year"</span>
        ExpiresByType application/vnd.ms-fontobject <span class="token string">"access plus 1 year"</span>
        ExpiresByType image/vnd.microsoft.icon <span class="token string">"access plus 1 month"</span>
    <span class="token operator">&lt;</span>/IfModule<span class="token operator">&gt;</span>

    RewriteEngine On

    <span class="token comment"># Redirect to preferred domain</span>
    RewriteCond %<span class="token punctuation">{</span>HTTP_HOST<span class="token punctuation">}</span> <span class="token operator">!</span><span class="token punctuation">(</span>^barryvanveen<span class="token punctuation">\</span>.test<span class="token operator">|</span>^barryvanveen<span class="token punctuation">\</span>.nl<span class="token punctuation">)</span>$ <span class="token punctuation">[</span>NC<span class="token punctuation">]</span>
    RewriteRule ^<span class="token punctuation">(</span>.*<span class="token punctuation">)</span>$ <a class="token url-link" href="https://barryvanveen.nl/">https://barryvanveen.nl/</a><span class="token variable">$1</span> <span class="token punctuation">[</span>R<span class="token operator">=</span><span class="token number">301</span>,L<span class="token punctuation">]</span>

    <span class="token comment"># Redirect old Dutch urls to English urls</span>
    RewriteRule ^over-mij$ <a class="token url-link" href="https://barryvanveen.nl/about-me">https://barryvanveen.nl/about-me</a> <span class="token punctuation">[</span>L,R<span class="token operator">=</span><span class="token number">301</span><span class="token punctuation">]</span>
    RewriteRule ^over-mij/boeken-die-ik-heb-gelezen$ <a class="token url-link" href="https://barryvanveen.nl/about-me/books-that-i-have-read">https://barryvanveen.nl/about-me/books-that-i-have-read</a> <span class="token punctuation">[</span>L,R<span class="token operator">=</span><span class="token number">301</span><span class="token punctuation">]</span>

    <span class="token comment"># Redirect to HTTPS domain</span>
    RewriteCond %<span class="token punctuation">{</span>HTTP_HOST<span class="token punctuation">}</span> ^barryvanveen.nl$ <span class="token punctuation">[</span>NC<span class="token punctuation">]</span>
    RewriteCond %<span class="token punctuation">{</span>HTTPS<span class="token punctuation">}</span> <span class="token operator">!=</span>on <span class="token punctuation">[</span>NC<span class="token punctuation">]</span>
    RewriteRule ^<span class="token punctuation">(</span>.*<span class="token punctuation">)</span>$ <a class="token url-link" href="https://barryvanveen.nl/">https://barryvanveen.nl/</a><span class="token variable">$1</span> <span class="token punctuation">[</span>R<span class="token operator">=</span><span class="token number">301</span>,L<span class="token punctuation">]</span>

    <span class="token comment"># Redirect assets with filehash in name to actual filename</span>
    RewriteRule ^dist/css/<span class="token punctuation">(</span>.*<span class="token punctuation">)</span><span class="token punctuation">\</span>.<span class="token punctuation">[</span><span class="token number">0</span>-9a-f<span class="token punctuation">]</span><span class="token punctuation">{</span><span class="token number">8</span><span class="token punctuation">}</span><span class="token punctuation">\</span>.css$ /dist/css/<span class="token variable">$1</span>.css <span class="token punctuation">[</span>L<span class="token punctuation">]</span>
    RewriteRule ^dist/js/<span class="token punctuation">(</span>.*<span class="token punctuation">)</span><span class="token punctuation">\</span>.<span class="token punctuation">[</span><span class="token number">0</span>-9a-f<span class="token punctuation">]</span><span class="token punctuation">{</span><span class="token number">8</span><span class="token punctuation">}</span><span class="token punctuation">\</span>.js$ /dist/js/<span class="token variable">$1</span>.js <span class="token punctuation">[</span>L<span class="token punctuation">]</span>

    <span class="token comment"># Remove trailing slashes if not a folder</span>
    RewriteCond %<span class="token punctuation">{</span>REQUEST_FILENAME<span class="token punctuation">}</span> <span class="token operator">!</span>-d
    RewriteCond %<span class="token punctuation">{</span>REQUEST_URI<span class="token punctuation">}</span> <span class="token punctuation">(</span>.+<span class="token punctuation">)</span>/$
    RewriteRule ^ %1 <span class="token punctuation">[</span>L,R<span class="token operator">=</span><span class="token number">301</span><span class="token punctuation">]</span>

    <span class="token comment"># Handle request using index.php</span>
    RewriteCond %<span class="token punctuation">{</span>REQUEST_FILENAME<span class="token punctuation">}</span> <span class="token operator">!</span>-d
    RewriteCond %<span class="token punctuation">{</span>REQUEST_FILENAME<span class="token punctuation">}</span> <span class="token operator">!</span>-f
    RewriteRule ^ index.php <span class="token punctuation">[</span>L<span class="token punctuation">]</span>
<span class="token operator">&lt;</span>/IfModule<span class="token operator">&gt;</span>
</code></pre>

    <pre class="  language-json"><code class="  language-json"><span class="token property">"repositories"</span><span class="token operator">:</span><span class="token punctuation">[</span>
    <span class="token punctuation">{</span>
        <span class="token property">"type"</span><span class="token operator">:</span> <span class="token string">"vcs"</span><span class="token punctuation">,</span>
        <span class="token property">"url"</span><span class="token operator">:</span> <span class="token string">"<a class="token email-link" href="mailto:git@github.com">git@github.com</a>:barryvanveen/secret.git"</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">]</span>
</code></pre>

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
