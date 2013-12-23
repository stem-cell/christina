## Christina

This is a code package to improve the MyImoutoBooru software on hypnohub.net.
It evolved from a smaller script, and now groups several facilities, which
will be managed as a single unit.

The name comes from a [Steins;Gate reference](http://www.youtube.com/watch?v=a-GqSWsISVs).

## Roadmap

I'll quote the wise Linus Torvalds on this one:

> Don't hurry your code. Make sure it works well and is well designed. Don't worry about timing.

## Codebase

This is the *current* codebase size, excluding compiled output (like the compiled CSS from LESS) and excluding code I didn't write myself. Note that none of the previous javascript is in the repository, yet.

    -------------------------------------------------------------------------------
    Language                     files          blank        comment           code
    -------------------------------------------------------------------------------
    PHP                             84            508            588           2200
    LESS                            11            131             59            546
    SQL                             12              0             17            177
    Bourne Shell                     2              4              4             21
    -------------------------------------------------------------------------------
    SUM:                           109            643            668           2944
    -------------------------------------------------------------------------------

It has been generated by [CLOC](http://cloc.sourceforge.net).

## Requirements

Besides from the basic requirements to be running MyImoutoBooru, make sure you have the MySQL Native Drivers. If you do, you would see a section like this in your PHPInfo:

<p align="center"><img alt="screenshot" src="https://raw.github.com/stem-cell/christina/master/doc/images/mysqlnd.png" /></p>

## License

Christina is licensed under a [Do What The Fuck You Want To Public License](http://www.wtfpl.net/).
