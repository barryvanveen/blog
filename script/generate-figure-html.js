class Figure {
    constructor(filename, caption, alt) {
        this.filename = filename;
        this.caption = caption;
        this.alt = alt;
    }
}

/**
 * @param {Figure} figure
 */
const printHtmlOutput = (figure) => {
    console.log(`
<figure>
  <a href="/images/${figure.filename}-original.png" title="View the full sized image" target="_blank">
    <img src="/images/${figure.filename}-750.png"
         srcset="/images/${figure.filename}-320.png 320w, /images/${figure.filename}-480.png 480w, /images/${figure.filename}-750.png 750w"
         sizes="(max-width: 320px) 320px, (max-width: 480px) 480px, 750px"
         alt="${figure.alt}"
         loading="lazy">
  </a>
  <figcaption>${figure.caption}</figcaption>
</figure>`)
}

const figures = [
    new Figure(
        '4-GA-filter-blokkeer-campagnebron',
        'Google Analytics filter op campagnebron',
        'Google Analytics filter op campagnebron'
    ),
    new Figure(
        '4-GA-filter-mijn-hostnamen',
        'Google Analytics filter op hostnamen',
        'Google Analytics filter op hostnamen'
    ),
    new Figure(
        '4-GA-gefilterd-doelgroepoverzicht',
        'Doelgroepoverzicht voor en na filters',
        'Doelgroepoverzicht voor en na filters'
    ),
    new Figure(
        '4-GA-lijst-met-verwijzingen-en-hostnamen',
        'Lijst met verwijzingen en bijbehorende hostnamen in Google Analytics',
        'Lijst met verwijzingen en bijbehorende hostnamen in Google Analytics'
    ),
    new Figure(
        '7-dropbox-app-aanmaken',
        'Een Dropbox app aanmaken',
        'Dropbox app aanmaken'
    ),
    new Figure(
        '7-dropbox-app-details',
        'Dropbox app details bekijken',
        'Dropbox app details bekijken'
    ),
    new Figure(
        '11-zoekresultaten-met-microdata',
        'Zoekresultaten met microdata',
        'Zoekresultaten met microdata'
    ),
    new Figure(
        '14-gitflow-workflow',
        'Gitflow branching model',
        'Gitflow branching model'
    ),
    new Figure(
        '20-pagespeed-insights-after-critical-path-css',
        'PageSpeed Insights\' suggestions after Critical Path CSS',
        'PageSpeed Insights\' suggestions after Critical Path CSS'
    ),
    new Figure(
        '27-gtm-define-datalayer-variable',
        'Create a new DataLayer variable',
        'Create a new DataLayer variable'
    ),
    new Figure(
        '27-gtm-define-errorcode-trigger',
        'Create a trigger for when the page contains an error code',
        'Create a new trigger that triggers when there is an error code on the page'
    ),
    new Figure(
        '27-gtm-define-tag-for-errorcode-events',
        'Create a tag to track events',
        'Create a new tag for registering error page events'
    ),
    new Figure(
        '33-composer-outdated-output',
        'Example of output of the command "composer outdated"',
        'Example of output of the command \"composer outdated\"'
    ),
    new Figure(
        '35-report-uri-report',
        'Report-uri.io report',
        'Report-uri.io report'
    ),
    new Figure(
        '78224837-ab-runner-comparison',
        'Comparison plot of 2 ab-runner tests',
        'Comparison of 2 benchmark tests. The cached version of the website is faster with a mean of 148ms, compared to a mean of 199ms for the uncached version'
    ),
    new Figure(
        'db584055-ab-runner-comparison',
        'Comparison plot of 3 ab-runner tests',
        'Comparison of 3 benchmark tests. When Docker\'s experimental features are enabled, response times drop from a mean of 785ms to a mean of 232ms.'
    ),
    new Figure(
        'db584055-docker-settings',
        'Docker\'s experimental features',
        'Screenshot of Docker (version 4.12.0) settings screen with experimental features enabled'
    ),
];

figures.forEach((figure) => {
    printHtmlOutput(figure);
})
