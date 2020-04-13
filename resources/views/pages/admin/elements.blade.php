@extends('layouts.base')

@section('body')

    <article itemprop="mainEntity" itemscope="" itemtype="https://schema.org/BlogPosting">
        <header>
            <h1 itemprop="headline">My title with a slightly longer texts so it takes more than a single line?</h1>
            <p>
                <time datetime="2019-12-30T21:00:00+01:00" itemprop="datePublished">Dec 30, 2019</time>
                &diamond; <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
                &diamond; <a href="/whiteglass/categories/junk/">junk</a>
                &diamond; <a href="/whiteglass/categories/junk/">2 comments</a>
            </p>
        </header>
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
            <ol>
                <li>Make a new user-defined variable called "errorcode".</li>
            </ol>
            <figure>
                <a href="images/gtm-define-datalayer-variable.png" title="View the full sized image" target="_blank">
                    <img src="/images/gtm-define-datalayer-variable.png?w=320" alt="Create a new DataLayer variable"></a>
                <figcaption>Create a new DataLayer variable</figcaption>
            </figure>
            <p>Set an empty default value (ie. "") so that you can be sure the variable exists on every page.</p>
            <ol start="2">
                <li>Create a new trigger called "Error code on page".</li>
            </ol>
            <figure>
                <a href="images/gtm-define-errorcode-trigger.png" title="View the full sized image" target="_blank">
                    <img src="images/gtm-define-errorcode-trigger.png?w=320" alt="Create a new trigger that triggers when there is an error code on the page"></a>
                <figcaption>Create a trigger for when the page contains an error code</figcaption>
            </figure>
            <p>This trigger will, ehm, trigger when the `errorcode` variable is non-zero.</p>
            <ol start="3">
                <li>Add a new tag called for tracking events when there is an errorcode on a page.</li>
            </ol>
            <figure>
                <a href="images/gtm-define-tag-for-errorcode-events.png" title="View the full sized image" target="_blank">
                    <img src="images/gtm-define-tag-for-errorcode-events.png?w=320" alt="Create a new tag for registering error page events"></a>
                <figcaption>Create a tag to track events</figcaption>
            </figure>
            <p>This tag uses our new variable as the name of our event. It is only called when our newly defined trigger matches.</p>

            <p><strong>Hope this solved your problem too. I've only tested these solutions on Laravel Homestead but they might apply to other setups. Leave me a comment if you have questions or suggestions.</strong></p>
        </div>
    </article>

@endsection
