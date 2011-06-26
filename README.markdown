# PeanutButter

PeanutButter is a really old blog/cms I wrote when I worked at Infineon. It makes nearly every mistake you could ever make when writing a blog - no frameworks/templating/whatever, passwords stored in plaintext in mysql, stuff like that. It's here for posterity, humility, and a good laugh.

It actually served as the basis for another project I worked on at RIT called `wtry`. I cleaned up a lot for that project, made the database access purty and clean, though I probably hadn't figured out that plaintext passwords were bad.

Here's a blog article I wrote about it back in 2005:

## Intro

**Peanutbutter** is a tiny bit of a twist on blogging. The need for this arose at my first co-op, when I was constantly barraged with “Hey, how far along are you on…?”, “Did you finish…?”, and “Can you tell me what you did today?”. I finally got sick of giving the same answers, over and over again, that I decided to create a central database of my project status. There were very few general requirements:

  It had to contain whatever information was pertinent to a given project (abstractly enough that I could represent any type of project that you could imagine: coding, documentation, building a new deck on your house, etc.).
  It had to contain the simplest blogging features – the ability for regular people to register and post comments about projects, and these privileges could be elevated (on a per-user basis) to allow managing project entries and posting site-wide news events.
  It had to have the minimal set of features possible to be functional.
  It had to provide some methodology for searching through projects, preferrably in some user-definable manner, so that users could easily pick and choose which projects to view.

…and that was really it. Everything else just happened along the way.

## Why “peanutbutter“?

So I wanted to name it something really stupid, like Project Blog, but then I figured that there are probably other, better project-based blogs out there, and it would be pretty presumptious of me to call it that (it would be like Dell being called “Computer”). So then, because I am entirely lacking the creativity gene, I decided to keep PB, because it is short, sweet, and doesn’t seem to contradict any other computer acronyms (that I know of). Well, the immediate thing that popped into my head was “hey, PB stands for peanut butter,” and I thought, “well, that would be a really stupid name. What else could I make PB stand for?” As it turns out, very little that I could come up with. After about 20 minutes of thinking (which really stretched my brain, let me tell you), I was unable to come up with anything interesting/memorable/instightful/cute/witty/whatever. So, giving in to my inability to come up with an interesting name, I stuck with peanutbutter, as a solemn reminder that I, Noah Richards, should never be allowed to name anything.

## Where is it?

*Note*: This is clearly not where it lives anymore :)

The code is GPL, but I have yet to put it in a very usable place. The CVS repository that I use for the project has anonymous cvs access, so, to grab peanutbutter, you would type:

<del>cvs -d :pserver:anoncvs@ephesus.dyndns.org:/usr/local/cvsroot co pb
Or, I currently have cvsweb setup on that box, so you could instead go here and see the repository. Either way, you’ll get to the same place.</del>

## Hey, you should fix…

…everything, mostly. Actually, I really do enjoy the criticism, as it makes my job much easier in the end, so send it my way (noah#at#noahsmark.com should get it to me). And if you want to make changes, again, please do. And if you want to make lots of changes and put it back in CVS, please do. If you want write access to the CVS repository, just ask me, and I can set up an account on my box (all non-anonymous access to the repository goes through SSH, no exceptions).

## Why isn’t this on sourceforge/freshmeat/etc.?

I submitted it to freshmeat before, but I didn’t have either a a)CVS repository or b)another method of distributing an up-to-date version of the code, so they (very correctly) told me to buzz off and stop wasting their time. This time, before I submit it, I’m going to make sure I have everything in order.
