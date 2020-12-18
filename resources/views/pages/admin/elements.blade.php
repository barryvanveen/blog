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
            <div class="highlight highlight-text-html-php"><pre><span class="pl-s">'remote_mysql'</span> =&gt; [
    <span class="pl-s">'driver'</span> =&gt; <span class="pl-s">'mysql'</span>,
    <span class="pl-s">'host'</span> =&gt; <span class="pl-en">env</span>(<span class="pl-s">'REMOTE_DB_HOST'</span>, <span class="pl-s">'127.0.0.1'</span>),
    <span class="pl-s">'port'</span> =&gt; <span class="pl-en">env</span>(<span class="pl-s">'REMOTE_DB_PORT'</span>, <span class="pl-s">'13306'</span>),
    <span class="pl-s">'database'</span> =&gt; <span class="pl-en">env</span>(<span class="pl-s">'REMOTE_DB_DATABASE'</span>, <span class="pl-s">'forge'</span>),
    <span class="pl-s">'username'</span> =&gt; <span class="pl-en">env</span>(<span class="pl-s">'REMOTE_DB_USERNAME'</span>, <span class="pl-s">'forge'</span>),
    <span class="pl-s">'password'</span> =&gt; <span class="pl-en">env</span>(<span class="pl-s">'REMOTE_DB_PASSWORD'</span>, <span class="pl-s">''</span>),
    <span class="pl-c">// ... </span>
],</pre></div>

            <div class="highlight highlight-text-html-php"><pre><span class="pl-k">class</span> <span class="pl-v">Article</span> <span class="pl-k">extends</span> <span class="pl-v">Model</span>
{
    <span class="pl-c">// column name of key</span>
    <span class="pl-k">protected</span> <span class="pl-c1"><span class="pl-c1">$</span>primaryKey</span> = <span class="pl-s">'uuid'</span>;

    <span class="pl-c">// type of key</span>
    <span class="pl-k">protected</span> <span class="pl-c1"><span class="pl-c1">$</span>keyType</span> = <span class="pl-s">'string'</span>;

    <span class="pl-c">// whether the key is automatically incremented or not</span>
    <span class="pl-k">public</span> <span class="pl-c1"><span class="pl-c1">$</span>incrementing</span> = <span class="pl-c1">false</span>;
}</pre></div>

            <div class="highlight highlight-source-shell"><pre><span class="pl-k">&lt;</span>IfModule mod_rewrite.c<span class="pl-k">&gt;</span>
    <span class="pl-k">&lt;</span>IfModule mod_negotiation.c<span class="pl-k">&gt;</span>
        Options -MultiViews
    <span class="pl-k">&lt;</span>/IfModule<span class="pl-k">&gt;</span>

    <span class="pl-c"><span class="pl-c">#</span> set Expire and Cache Control headers for css and js</span>
    <span class="pl-k">&lt;</span>IfModule mod_expires.c<span class="pl-k">&gt;</span>
        ExpiresActive On
        ExpiresDefault <span class="pl-s"><span class="pl-pds">"</span>access<span class="pl-pds">"</span></span>
        ExpiresByType text/css <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>
        ExpiresByType application/javascript <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>

        ExpiresByType font/truetype <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>
        ExpiresByType font/opentype <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>
        ExpiresByType application/x-font-woff <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>
        ExpiresByType image/svg+xml <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>
        ExpiresByType application/vnd.ms-fontobject <span class="pl-s"><span class="pl-pds">"</span>access plus 1 year<span class="pl-pds">"</span></span>
        ExpiresByType image/vnd.microsoft.icon <span class="pl-s"><span class="pl-pds">"</span>access plus 1 month<span class="pl-pds">"</span></span>
    <span class="pl-k">&lt;</span>/IfModule<span class="pl-k">&gt;</span>

    RewriteEngine On

    <span class="pl-c"><span class="pl-c">#</span> Redirect to preferred domain</span>
    RewriteCond %{HTTP_HOST} <span class="pl-k">!</span>(^barryvanveen<span class="pl-cce">\.</span>test<span class="pl-k">|</span>^barryvanveen<span class="pl-cce">\.</span>nl)$ [NC]
    RewriteRule ^(.<span class="pl-k">*</span>)$ https://barryvanveen.nl/<span class="pl-smi">$1</span> [R<span class="pl-k">=</span>301,L]

    <span class="pl-c"><span class="pl-c">#</span> Redirect old Dutch urls to English urls</span>
    RewriteRule ^over-mij$ https://barryvanveen.nl/about-me [L,R<span class="pl-k">=</span>301]
    RewriteRule ^over-mij/boeken-die-ik-heb-gelezen$ https://barryvanveen.nl/about-me/books-that-i-have-read [L,R<span class="pl-k">=</span>301]

    <span class="pl-c"><span class="pl-c">#</span> Redirect to HTTPS domain</span>
    RewriteCond %{HTTP_HOST} ^barryvanveen.nl$ [NC]
    RewriteCond %{HTTPS} <span class="pl-k">!</span>=on [NC]
    RewriteRule ^(.<span class="pl-k">*</span>)$ https://barryvanveen.nl/<span class="pl-smi">$1</span> [R<span class="pl-k">=</span>301,L]

    <span class="pl-c"><span class="pl-c">#</span> Redirect assets with filehash in name to actual filename</span>
    RewriteRule ^dist/css/(.<span class="pl-k">*</span>)<span class="pl-cce">\.</span>[0-9a-f]{8}<span class="pl-cce">\.</span>css$ /dist/css/<span class="pl-smi">$1</span>.css [L]
    RewriteRule ^dist/js/(.<span class="pl-k">*</span>)<span class="pl-cce">\.</span>[0-9a-f]{8}<span class="pl-cce">\.</span>js$ /dist/js/<span class="pl-smi">$1</span>.js [L]

    <span class="pl-c"><span class="pl-c">#</span> Remove trailing slashes if not a folder</span>
    RewriteCond %{REQUEST_FILENAME} <span class="pl-k">!</span>-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R<span class="pl-k">=</span>301]

    <span class="pl-c"><span class="pl-c">#</span> Handle request using index.php</span>
    RewriteCond %{REQUEST_FILENAME} <span class="pl-k">!</span>-d
    RewriteCond %{REQUEST_FILENAME} <span class="pl-k">!</span>-f
    RewriteRule ^ index.php [L]
<span class="pl-k">&lt;</span>/IfModule<span class="pl-k">&gt;</span></pre></div>

    <div class="highlight highlight-source-json"><pre><span class="pl-s"><span class="pl-pds">"</span>repositories<span class="pl-pds">"</span></span>:[
    {
        <span class="pl-s"><span class="pl-pds">"</span>type<span class="pl-pds">"</span></span>: <span class="pl-s"><span class="pl-pds">"</span>vcs<span class="pl-pds">"</span></span>,
        <span class="pl-s"><span class="pl-pds">"</span>url<span class="pl-pds">"</span></span>: <span class="pl-s"><span class="pl-pds">"</span>git@github.com:barryvanveen/secret.git<span class="pl-pds">"</span></span>
    }
]</pre></div>

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

@endsection
