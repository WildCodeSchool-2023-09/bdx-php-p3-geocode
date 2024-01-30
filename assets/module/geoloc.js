import {Point} from "./Point";

export function degToRad(Value)
{
    return Value * Math.PI / 180;
}

/**
 * find the next point from start where distance is between stepLength +- marginOfError
 *
 * @param start
 * @param pointList
 * @param stepLength
 * @param marginOfError
 */
export function findNextStep(start, pointList, stepLength = 100, marginOfError = 10)
{
    let len = pointList.length;
    if (len === 0) {
        console.error('POINTLIST EMPTY')
        return;
    }
    if (len === 1) {
        return pointList[0];
    }
    if (!(start instanceof Point)) {
        start = new Point(start);
    }

    let maxDistance = start.calcDistanceWith(new Point(pointList[len - 1]));
    let minimumStep = stepLength - marginOfError;
    let maximumStep = stepLength + marginOfError;
    if (maxDistance >= minimumStep && maxDistance <= maximumStep) {
        return pointList[len - 1];
    }

    let middle = Math.ceil(len / 2);
    let middleDistance = start.calcDistanceWith(new Point(pointList[middle]));
    let newList;

    if (middleDistance >= minimumStep && middleDistance <= maximumStep) {
        return pointList[middle];
    } else if (middleDistance > minimumStep ) {
        newList = pointList.slice(0, middle + 1);
    } else {
        newList = pointList.slice(middle, -1);
    }
    return findNextStep(start,newList, stepLength, marginOfError);
}

export function findAllSteps(pointList, stepLength = 100, marginOfError = 10)
{
    const steps = [];
    let step = pointList[0];
    let stepPoint = new Point(pointList[0]);
    let end = new Point(pointList[pointList.length - 1]);

    while (stepPoint.calcDistanceWith(end) > stepLength) {
        steps.push(stepPoint);

        step = findNextStep(stepPoint, pointList, stepLength, marginOfError);
        let index = pointList.indexOf(step);
        stepPoint = new Point(step);
        pointList = pointList.slice(index, -1);
    }
    steps.push(stepPoint);
    if (stepPoint.calcDistanceWith(pointList[pointList.length - 1]) > 50) {
        steps.push(new Point(pointList[pointList.length - 1]));
    }
    return steps;
}
