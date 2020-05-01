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
            <pre><code class="language-php">class Article extends Model
{
    // column name of key
    protected $primaryKey = 'uuid';

    // type of key
    protected $keyType = 'string';

    // whether the key is automatically incremented or not
    public $incrementing = false;
}
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

@endsection
