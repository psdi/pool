/* helper functions */
// src: https://stackoverflow.com/a/9541579
function isOverflown(element) {
    return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
}

/* main functions */
function toObject(entries) {
    return Array.from(entries).map(entry => {
        return $(entry).data();
    });
}

function updateEditor(entry) {
    selectedEntry = entry;
    $('.editor-title').val(entry.title);
    $('.editor-text').val(entry.text);
}

function updateSidebar($activeEntry) {
    $('.entries').prepend($activeEntry);
}

function onInput() {
    let timestamp = Date.now();
    let field = $(this).hasClass('editor-title') ? 'title' : 'text';
    let fieldText = $(this).val();
    let $activeEntry = $('.active.pool-entry');

    // ...and edit only the object information here?
    // search for the one with the matching id and edit jquery bzw. dom object there
    if (field === 'title') {
        $activeEntry.attr('data-title', fieldText);
        if (!isOverflown($activeEntry.children('.entry-title'))) {
            $( $activeEntry.children('.entry-title') ).text(fieldText);
        }
        $activeEntry.children('.entry-title').val(fieldText);
    } else if (field === 'text') {
        $activeEntry.attr('data-text', fieldText);
        if (!isOverflown($activeEntry.children('.entry-preview'))) {
            $( $activeEntry.children('.entry-preview') ).text(fieldText);
        }
    }

    if ($activeEntry.data('id') !== $('.pool-entry').eq(0).data('id')) {
        updateSidebar($activeEntry);
    }

    entries = toObject( $('.pool-entry') );
    selectedEntry = entries[0];
};

$('.editor-text').on('input', onInput);
$('.editor-title').on('input', onInput);

// $('.editor-text').on('input', function() {
    // on edit, change the timestamp
    // if not already the first element, remove and prepend it to the first .pool-entry element
    // change the data-attributes of the thing
    // change displayed text, if needed (e.g. text wird kürzer)
// });

// delegated event handler
$('.entries').on('click', '.pool-entry', function() {
    $('.pool-entry').removeClass('active');
    $(this).addClass('active');
    updateEditor($(this).data());
});

let entries = toObject( $('.pool-entry') ); // what if you put this in a function, and see my comment above
let selectedEntry = entries[0];
updateEditor(selectedEntry);

/* http://jscrollpane.kelvinluck.com/ */
